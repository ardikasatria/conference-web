# Reviewer System Documentation

This document outlines the complete reviewer system for the conference management platform.

## Overview

The reviewer system allows registered participants to apply for reviewer roles, get approved by admins, and review submitted papers using a structured grading rubric.

### Key Components
- **Topics Management**: Conference-specific categories/tracks
- **Reviewer Applications**: Multi-field application form for participants to apply as reviewers
- **Grading Criteria**: Scoring rubric per conference (e.g., Originality, Methodology, Clarity)
- **Paper Review Workflow**: Full review process with Q&A and scoring

## Database Schema

### Topics Table (`topics`)
Categorizes submissions by research area or track.

```
id              | bigint(20)
name            | varchar(255)  [UNIQUE]
description     | text
slug            | varchar(255)  [UNIQUE]
timestamps      | created_at, updated_at
soft_deletes    | deleted_at
```

**Example Topics:**
- Artificial Intelligence
- Machine Learning
- Data Science
- Cloud Computing
- Software Engineering

### Conference Topics Pivot (`conference_topics`)
Links conferences with available topics, supporting multiple tracks per Conference.

```
conference_id   | bigint(20)  [FK → conferences]
topic_id        | bigint(20)  [FK → topics]
order           | integer
primary_key     | (conference_id, topic_id)
timestamps      | created_at, updated_at
```

### Enhanced Reviewer Applications (`reviewer_applications`)
Extended with detailed application fields.

**New Fields in ALTER migration:**
```
field_of_study          | varchar(255)  [e.g., "Computer Science"]
sub_field               | varchar(255)  [e.g., "Machine Learning"]
selected_topics         | json          [Array of topic IDs]
full_name_with_degree   | varchar(255)  [e.g., "Dr. John Smith, PhD"]
affiliation             | varchar(255)  [e.g., "MIT", "Stanford"]
```

### Grading Criteria (`grading_criteria`)
Defines scoring rubric per conference.

```
id              | bigint(20)
conference_id   | bigint(20)  [FK → conferences, CASCADE]
name            | varchar(255)      [e.g., "Originality"]
description     | text
max_score       | decimal(5, 2)     [e.g., 10.00]
order           | integer            [Display order]
timestamps      | created_at, updated_at
```

**Common Criteria Examples:**
| Name | Max Score | Description |
|------|-----------|-------------|
| Originality | 10 | Is the work novel and original? |
| Methodology | 10 | Are research methods sound? |
| Clarity | 10 | Is the paper well-written and clear? |
| Significance | 10 | Does it contribute to the field? |
| Relevance | 10 | Is it relevant to conference topics? |

### Paper Reviews (`paper_reviews`)
Main review record for each submission review.

```
id              | bigint(20)
submission_id   | bigint(20)  [FK → submissions, CASCADE]
reviewer_id     | bigint(20)  [FK → users, CASCADE]
conference_id   | bigint(20)  [FK → conferences, CASCADE]
review_date     | datetime
total_score     | decimal(5, 2)  [Calculated from grades]
status          | enum('pending', 'in_progress', 'completed', 'accepted', 'rejected')
comments        | longtext        [Overall review comments]
recommendation  | text            [Final recommendation]
recommend_accept| boolean         [null, true, or false]
timestamps      | created_at, updated_at
soft_deletes    | deleted_at
```

**Status Flow:**
```
pending → in_progress → completed → [accepted OR rejected]
```

### Review Grades (`review_grades`)
Individual scores per grading criteria.

```
id                  | bigint(20)
paper_review_id     | bigint(20)  [FK → paper_reviews, CASCADE]
grading_criteria_id | bigint(20)  [FK → grading_criteria, CASCADE]
score               | decimal(5, 2)
notes               | text            [Optional notes for this criteria]
timestamps          | created_at, updated_at
```

### Review Questions (`review_questions`)
Q&A section within a review for structured feedback.

```
id              | bigint(20)
paper_review_id | bigint(20)  [FK → paper_reviews, CASCADE]
question        | text        [Predefined or custom question]
answer          | text        [Reviewer's answer/feedback]
order           | integer     [Display order]
timestamps      | created_at, updated_at
```

## Models & Relationships

### Topic Model
```php
use App\Models\Topic;

// Get all conferences with this topic
$topic->conferences();

// Retrieve with pivot data
$topic = Topic::with('conferences')->find($id);
```

### GradingCriteria Model
```php
use App\Models\GradingCriteria;

// Get all grades for this criteria across reviews
$criteria->reviewGrades();

// Get parent conference
$criteria->conference();
```

### PaperReview Model
```php
use App\Models\PaperReview;

$review = PaperReview::with(['submission', 'reviewer', 'grades', 'questions'])->find($id);

// Core relationships
$review->submission();      // The submitted paper
$review->reviewer();        // User who reviewed
$review->conference();      // Parent conference
$review->grades();          // All ReviewGrade records
$review->questions();       // All ReviewQuestion records (ordered)

// Methods
$review->addGrade($criteriaId, $score, $notes);
$review->calculateTotalScore();          // Sums all grades
$review->addQuestion($question, $answer, $order);
$review->markAsCompleted();
$review->submitReview($recommendation, $recommendAccept);
$review->isComplete();                   // Checks if all criteria graded
$review->getProgressPercentage();        // % of criteria graded

// Status tracking
$review->status;              // 'pending' | 'in_progress' | 'completed' | 'accepted' | 'rejected'
$review->review_date;
$review->total_score;         // Calculated field
$review->recommend_accept;    // null | true | false
```

### ReviewGrade Model
```php
use App\Models\ReviewGrade;

$grade = ReviewGrade::find($id);

// Relationships
$grade->paperReview();     // Parent PaperReview
$grade->criteria();        // GradingCriteria reference
```

### ReviewQuestion Model
```php
use App\Models\ReviewQuestion;

$question = ReviewQuestion::find($id);

// Relationships
$question->paperReview();  // Parent PaperReview

// Methods
$question->updateAnswer($answer);
```

### ReviewerApplication Model (Enhanced)
```php
use App\Models\ReviewerApplication;

$application = ReviewerApplication::with(['user', 'conference', 'topics'])->find($id);

// New fields
$application->field_of_study;        // "Computer Science"
$application->sub_field;             // "Machine Learning"
$application->selected_topics;       // [1, 3, 5] (array from JSON)
$application->full_name_with_degree; // "Dr. John Smith, PhD"
$application->affiliation;           // "MIT"

// Relationships
$application->topics();              // Belongsto-many Topic

// Methods (unchanged)
$application->approve($adminId, $notes);   // Auto-assigns reviewer role
$application->reject($adminId, $notes);
```

### Conference Model (Enhanced)
```php
use App\Models\Conference;

// New relationships
$conference->topics();              // Available topics/tracks
$conference->gradingCriteria();     // Scoring rubric
$conference->paperReviews();        // All reviews for submissions

// Usage
$criteria = $conference->gradingCriteria()->orderBy('order')->get();
foreach ($criteria as $c) {
    echo $c->name . " (max: {$c->max_score})";
}
```

## Workflow: Becoming a Reviewer

### 1. User Initiative
- User is already registered for conference (Registration record exists)
- Click "Become a Reviewer" button on user dashboard

### 2. Application Form
User fills:
- **Motivation**: Why they want to be reviewer
- **Expertise**: Areas of expertise
- **Field of Study**: e.g., "Computer Science"
- **Sub-field**: e.g., "Machine Learning"
- **Selected Topics**: Multi-select from `conference->topics()`
- **Full Name with Degree**: e.g., "Dr. John Smith, PhD"
- **Affiliation**: Institution/Organization

```php
// Backend validates and creates
$application = ReviewerApplication::create([
    'user_id' => auth()->id(),
    'conference_id' => $conferenceId,
    'motivation' => $form['motivation'],
    'expertise' => $form['expertise'],
    'field_of_study' => $form['field_of_study'],
    'sub_field' => $form['sub_field'],
    'selected_topics' => $form['selected_topics'], // Array
    'full_name_with_degree' => $form['full_name_with_degree'],
    'affiliation' => $form['affiliation'],
    'status' => 'pending',
]);
```

### 3. Admin Review
Admin navigates to "Reviewer Applications" dashboard and reviews pending applications:

```php
$pendingApps = $conference->pendingReviewerApplications()
    ->with('user', 'topics')
    ->get();

foreach ($pendingApps as $app) {
    echo "{$app->full_name_with_degree} - {$app->affiliation}";
    echo "Topics: " . implode(', ', $app->topics->pluck('name')->toArray());
}
```

### 4. Approval/Rejection
Admin approves application:

```php
$application->approve($adminId, 'Approved - Strong expertise');
// This automatically:
// - Sets status to 'approved'
// - Assigns 'reviewer' role to user in this conference
// - Records reviewed_at timestamp
// - Stores admin notes
```

Or rejects:

```php
$application->reject($adminId, 'Please reapply with more ML expertise');
```

## Workflow: Reviewing a Paper

### 1. Assignment
Admin assigns submission to reviewer:

```php
$review = PaperReview::create([
    'submission_id' => $submissionId,
    'reviewer_id' => $reviewerId,
    'conference_id' => $conferenceId,
    'status' => 'pending',
]);
```

### 2. Reviewer Starts Review
Reviewer views paper and clicks "Start Review":

```php
$review->update(['status' => 'in_progress']);
```

### 3. Grading Criteria
Reviewer scores each criteria:

```php
$criteria = $conference->gradingCriteria()->orderBy('order')->get();

foreach ($criteria as $c) {
    $review->addGrade(
        $c->id,
        $score,      // 0 - 10
        $notes       // Brief explanation
    );
}

// Calculate total (auto sums all individual grades)
$totalScore = $review->calculateTotalScore();
```

### 4. Q&A Section
Conference can define standard questions or allow reviewers to add:

```php
// Add predefined question
$review->addQuestion(
    question: "Does the paper address gaps in existing research?",
    answer: $reviewerAnswer,
    order: 1
);

// Or bulk add
foreach ($questions as $q) {
    $review->addQuestion($q['question'], null, $q['order']);
}

// Later, reviewer updates answers
$reviewQuestion->updateAnswer($answer);
```

### 5. Submit Review
Reviewer submits final recommendation:

```php
$review->submitReview(
    recommendation: "Accept with minor revisions",
    recommendAccept: true  // null = needs review, true = accept, false = reject
);

// This:
// - Calculation total_score if not already done
// - Sets status to 'completed' or 'accepted'/'rejected'
// - Records review_date
// - Stores recommendation text
```

### 6. Admin Review Decision
Admin can view all reviewer recommendations and make final decision:

```php
$submission = Submission::find($id);
$reviews = $submission->reviews()->with('reviewer')->get();

foreach ($reviews as $review) {
    echo "{$review->reviewer->name}: Score {$review->total_score}, ";
    echo "Recommends: " . ($review->recommend_accept ? 'ACCEPT' : 'REJECT');
    echo "\nFeedback: {$review->recommendation}";
}

// Admin approves based on reviews
$submission->approve();
```

## API Endpoints (To Be Implemented)

### Reviewer Application
```
POST   /api/reviewer-applications
GET    /api/reviewer-applications/{id}
PUT    /api/reviewer-applications/{id}
DELETE /api/reviewer-applications/{id}

// Admin only
GET    /api/conferences/{id}/reviewer-applications?status=pending
POST   /api/reviewer-applications/{id}/approve
POST   /api/reviewer-applications/{id}/reject
```

### Paper Reviews
```
POST   /api/paper-reviews
GET    /api/paper-reviews/{id}
PUT    /api/paper-reviews/{id}
DELETE /api/paper-reviews/{id}

// Reviewer operations
POST   /api/paper-reviews/{id}/start-review
POST   /api/paper-reviews/{id}/add-grade
POST   /api/paper-reviews/{id}/submit-review

// Admin operations
GET    /api/conferences/{id}/paper-reviews
GET    /api/submissions/{id}/reviews
```

### Topics & Criteria
```
GET    /api/conferences/{id}/topics
GET    /api/conferences/{id}/grading-criteria
POST   /api/conferences/{id}/grading-criteria (admin)
```

## Query Examples

### Get reviewers for a conference
```php
$reviewers = $conference->usersWithRole('reviewer');
```

### Get pending reviews
```php
$pending = $conference->paperReviews()
    ->where('status', 'in_progress')
    ->with('reviewer', 'submission')
    ->get();
```

### Get reviewer's assignments
```php
$reviews = PaperReview::where('reviewer_id', auth()->id())
    ->where('conference_id', $conferenceId)
    ->with('submission.registration.user')
    ->get();
```

### High-scoring reviews
```php
$highScores = $conference->paperReviews()
    ->where('total_score', '>=', 40)  // Out of 50
    ->with('submission')
    ->get();
```

### Get topics for dropdown
```php
$topics = Topic::all();
$conferenceTopics = $conference->topics()->get();
```

## Frontend Integration Notes

### Reviewer Application Form
- Field: "Field of Study" (text input)
- Field: "Sub-field" (text input)
- Field: "Topics" (multi-select dropdown from `conference->topics()`)
- Field: "Full Name with Degree" (text input)
- Field: "Affiliation" (text input)
- Field: "Motivation" (textarea)
- Field: "Expertise" (textarea)

### Paper Review Interface
- Display paper (submission.file_path)
- Display grading rubric (conference->gradingCriteria)
- Score input for each criteria (0 - max_score)
- Notes field for each criteria
- Questions section (predefined + answers)
- Overall recommendation dropdown
- Submit button (calls submitReview)
- Progress bar showing % of criteria graded

### Admin Dashboard
- Pending applications list with topics, affiliation
- Approve/Reject buttons
- Paper reviews list: submission title, reviewer, status, score
- Filter by: Conference, Topic, Status, Date range
- Export reviews as PDF/Excel

## Status Transitions Diagram

```
ReviewerApplication:
pending → [approved OR rejected]

PaperReview:
pending ↔ in_progress → completed → [accepted OR rejected]
```

## Notes

- JSON field `selected_topics` stores array of topic IDs for quick filtering
- `total_score` is calculated and stored for performance (denormalized)
- Soft deletes on PaperReview/ReviewerApplication for audit trail
- Cascade deletes on review data when submission deleted
- Score validation: ReviewGrade.score must be ≤ GradingCriteria.max_score
- All timestamps in UTC

## Future Enhancements

- Email notifications on application status change
- Reviewer conflict of interest check
- Anonymous review mode toggle per conference
- Review deadline enforcement
- Reviewer workload balancing
- Appeal/Discussion mechanism between reviewers
- Recommendation consensus calculation
