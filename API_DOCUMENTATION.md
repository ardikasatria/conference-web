# API Routes & Controllers Documentation

## Overview

Complete REST API implementation for the conference reviewer system with:
- **150 lines** of API routes
- **6 Controllers** for reviewer functionality
- Full authentication & authorization
- Comprehensive request/response examples

---

## API Routes Structure

### File: `routes/api.php`

#### 1. **Reviewer Applications** (`/api/reviewer-applications`)

```
POST   /api/reviewer-applications              → ReviewerApplicationController@store
GET    /api/reviewer-applications/{id}         → ReviewerApplicationController@show
PUT    /api/reviewer-applications/{id}         → ReviewerApplicationController@update
DELETE /api/reviewer-applications/{id}         → ReviewerApplicationController@destroy

# Admin Only
POST   /api/reviewer-applications/{id}/approve → ReviewerApplicationController@approve
POST   /api/reviewer-applications/{id}/reject  → ReviewerApplicationController@reject

# Authenticated User
GET    /api/reviewer/applications              → ReviewerApplicationController@myApplications
GET    /api/reviewer/applications/{id}         → ReviewerApplicationController@myApplicationDetail
```

#### 2. **Paper Reviews** (`/api/paper-reviews`)

```
POST   /api/paper-reviews                      → PaperReviewController@store (admin)
GET    /api/paper-reviews/{id}                 → PaperReviewController@show
PUT    /api/paper-reviews/{id}                 → PaperReviewController@update
DELETE /api/paper-reviews/{id}                 → PaperReviewController@destroy (admin)

POST   /api/paper-reviews/{id}/start-review    → PaperReviewController@startReview
POST   /api/paper-reviews/{id}/add-grade       → PaperReviewController@addGrade
POST   /api/paper-reviews/{id}/submit-review   → PaperReviewController@submitReview
GET    /api/paper-reviews/{id}/progress        → PaperReviewController@progress

# Reviewer Dashboard
GET    /api/reviewer/reviews                   → PaperReviewController@myReviews
GET    /api/reviewer/reviews/{id}              → PaperReviewController@myReviewDetail
GET    /api/reviewer/statistics                → PaperReviewController@myStatistics
```

#### 3. **Submission Reviews** (`/api/submissions`)

```
GET    /api/submissions/{id}/reviews           → PaperReviewController@submissionReviews (admin)
```

#### 4. **Conference-Specific** (`/api/conferences/{conferenceId}`)

```
# Reviewer Applications
GET    /api/conferences/{id}/reviewer-applications           → ReviewerApplicationController@conferenceApplications (admin)
GET    /api/conferences/{id}/reviewer-applications/pending   → ReviewerApplicationController@pendingApplications (admin)

# Paper Reviews
GET    /api/conferences/{id}/paper-reviews                   → PaperReviewController@conferenceReviews (admin)

# Topics
GET    /api/conferences/{id}/topics            → TopicController@conferenceTopics
POST   /api/conferences/{id}/topics            → TopicController@attachTopic (admin)
DELETE /api/conferences/{id}/topics/{topicId}  → TopicController@detachTopic (admin)

# Grading Criteria
GET    /api/conferences/{id}/grading-criteria  → GradingCriteriaController@conferenceCriteria
POST   /api/conferences/{id}/grading-criteria  → GradingCriteriaController@store (admin)
PUT    /api/conferences/{id}/grading-criteria/{id}  → GradingCriteriaController@update (admin)
DELETE /api/conferences/{id}/grading-criteria/{id}  → GradingCriteriaController@destroy (admin)
```

#### 5. **Topics** (`/api/topics`)

```
GET    /api/topics                             → TopicController@index
POST   /api/topics                             → TopicController@store (admin)
GET    /api/topics/{id}                        → TopicController@show
PUT    /api/topics/{id}                        → TopicController@update (admin)
DELETE /api/topics/{id}                        → TopicController@destroy (admin)
```

#### 6. **Grading Criteria** (`/api/grading-criteria`)

```
GET    /api/grading-criteria                   → GradingCriteriaController@index
GET    /api/grading-criteria/{id}              → GradingCriteriaController@show
```

#### 7. **Review Grades** (`/api/review-grades`)

```
POST   /api/review-grades                      → ReviewGradeController@store
PUT    /api/review-grades/{id}                 → ReviewGradeController@update
DELETE /api/review-grades/{id}                 → ReviewGradeController@destroy
```

#### 8. **Review Questions** (`/api/review-questions`)

```
GET    /api/review-questions/{id}              → ReviewQuestionController@show
PUT    /api/review-questions/{id}/update-answer     → ReviewQuestionController@updateAnswer
POST   /api/review-questions/{id}/delete-answer     → ReviewQuestionController@deleteAnswer
```

---

## Controllers Overview

### 1. ReviewerApplicationController

**Methods:**
- `store()` - Create new application
- `show()` - View specific application
- `update()` - Update pending application
- `destroy()` - Delete pending application
- `conferenceApplications()` - List all applications (admin, paginated)
- `pendingApplications()` - List pending only (admin)
- `approve()` - Approve application (auto-assign reviewer role)
- `reject()` - Reject application
- `myApplications()` - Get authenticated user's applications
- `myApplicationDetail()` - Get authenticated user's specific application

**Validation Rules:**
```php
'conference_id' => 'required|exists:conferences,id'
'motivation' => 'required|string|min:50|max:1000'
'expertise' => 'required|string|min:50|max:1000'
'field_of_study' => 'required|string|max:255'
'sub_field' => 'required|string|max:255'
'selected_topics' => 'required|array|min:1'
'selected_topics.*' => 'exists:topics,id'
'full_name_with_degree' => 'required|string|max:255'
'affiliation' => 'required|string|max:255'
```

### 2. PaperReviewController

**Methods:**
- `store()` - Create review assignment (admin)
- `show()` - View review detail
- `update()` - Update review
- `destroy()` - Delete review (admin)
- `startReview()` - Mark as in_progress
- `addGrade()` - Add/update criteria score
- `submitReview()` - Submit final review
- `progress()` - Get review completion percentage
- `submissionReviews()` - Get all reviews for submission (admin)
- `conferenceReviews()` - List all reviews for conference (admin)
- `myReviews()` - Get assigned reviews (reviewer)
- `myReviewDetail()` - Get review detail (reviewer)
- `myStatistics()` - Get reviewer statistics

**Validation Rules:**
```php
// Create
'submission_id' => 'required|exists:submissions,id'
'reviewer_id' => 'required|exists:users,id'
'conference_id' => 'required|exists:conferences,id'

// Add Grade
'grading_criteria_id' => 'required|exists:grading_criteria,id'
'score' => 'required|numeric|min:0'
'notes' => 'nullable|string|max:500'

// Submit Review
'recommendation' => 'required|string|min:20|max:2000'
'recommend_accept' => 'nullable|boolean'
```

### 3. TopicController

**Methods:**
- `index()` - List all topics (searchable, paginated)
- `store()` - Create topic (admin)
- `show()` - View topic with conferences
- `update()` - Update topic (admin, slug auto-generated)
- `destroy()` - Delete topic (admin, validation on usage)
- `conferenceTopics()` - List topics for conference
- `attachTopic()` - Add topic to conference (admin)
- `detachTopic()` - Remove topic from conference (admin)

**Validations:**
- Name unique across topics
- Slug auto-generated from name if not provided
- Cannot delete topics assigned to conferences
- Topics ordered by name

### 4. GradingCriteriaController

**Methods:**
- `index()` - List all criteria (searchable, paginated)
- `store()` - Create criteria for conference (admin)
- `show()` - View criteria with statistics
- `update()` - Update criteria (admin)
- `destroy()` - Delete criteria (admin, validation on usage)
- `conferenceCriteria()` - Get conference scoring rubric with optional stats

**Features:**
- Automatic order assignment
- Score statistics calculation (avg, min, max)
- Prevention of deletion if grades exist
- Conference-specific rubrics

**Validations:**
```php
'name' => 'required|string|max:255'
'description' => 'nullable|string|max:1000'
'max_score' => 'required|numeric|min:1|max:100'
'order' => 'nullable|integer|min:0'
```

### 5. ReviewGradeController

**Methods:**
- `store()` - Create grade
- `update()` - Update grade
- `destroy()` - Delete grade

**Validations:**
- Score cannot exceed criteria's max_score
- Only assigned reviewer can modify
- Cascade delete from review

### 6. ReviewQuestionController

**Methods:**
- `show()` - Get question
- `updateAnswer()` - Add/update answer
- `deleteAnswer()` - Clear answer

**Validations:**
- Answer max 2000 characters
- Only assigned reviewer can update
- Authorization checks enabled

---

## Request/Response Examples

### Example 1: Create Reviewer Application

**Request:**
```bash
POST /api/reviewer-applications
Content-Type: application/json
Authorization: Bearer {token}

{
  "conference_id": 1,
  "motivation": "I have 10 years of experience in machine learning and want to contribute to peer review process...",
  "expertise": "Deep Learning, Computer Vision, Neural Networks, Transfer Learning",
  "field_of_study": "Computer Science",
  "sub_field": "Machine Learning",
  "selected_topics": [1, 3, 5],
  "full_name_with_degree": "Dr. Sarah Johnson, PhD",
  "affiliation": "Stanford University"
}
```

**Response (201):**
```json
{
  "message": "Application submitted successfully",
  "application": {
    "id": 1,
    "user_id": 5,
    "conference_id": 1,
    "motivation": "I have 10 years of...",
    "expertise": "Deep Learning, Computer Vision...",
    "field_of_study": "Computer Science",
    "sub_field": "Machine Learning",
    "selected_topics": [1, 3, 5],
    "full_name_with_degree": "Dr. Sarah Johnson, PhD",
    "affiliation": "Stanford University",
    "status": "pending",
    "created_at": "2026-03-08T10:30:00Z",
    "topics": [
      {"id": 1, "name": "Artificial Intelligence"},
      {"id": 3, "name": "Deep Learning"},
      {"id": 5, "name": "Computer Vision"}
    ],
    "user": {"id": 5, "name": "Sarah Johnson", "email": "sarah@stanford.edu"}
  }
}
```

### Example 2: Admin Approves Application

**Request:**
```bash
POST /api/reviewer-applications/1/approve
Content-Type: application/json
Authorization: Bearer {admin_token}

{
  "notes": "Excellent background. Approved to review AI and ML papers."
}
```

**Response (200):**
```json
{
  "message": "Application approved",
  "application": {
    "id": 1,
    "status": "approved",
    "reviewed_by": 1,
    "reviewed_at": "2026-03-08T11:00:00Z",
    "admin_notes": "Excellent background..."
  }
}
```

### Example 3: Admin Assigns Paper to Reviewer

**Request:**
```bash
POST /api/paper-reviews
Content-Type: application/json
Authorization: Bearer {admin_token}

{
  "submission_id": 15,
  "reviewer_id": 5,
  "conference_id": 1
}
```

**Response (201):**
```json
{
  "message": "Review assigned",
  "review": {
    "id": 42,
    "submission_id": 15,
    "reviewer_id": 5,
    "conference_id": 1,
    "status": "pending",
    "total_score": null,
    "created_at": "2026-03-08T12:00:00Z",
    "submission": {"id": 15, "title": "Deep Learning for Time Series..."},
    "reviewer": {"id": 5, "name": "Dr. Sarah Johnson"},
    "conference": {"id": 1, "name": "AI Conference 2026"}
  }
}
```

### Example 4: Reviewer Starts Review

**Request:**
```bash
POST /api/paper-reviews/42/start-review
Authorization: Bearer {reviewer_token}
```

**Response (200):**
```json
{
  "message": "Review started",
  "status": "in_progress"
}
```

### Example 5: Reviewer Adds Grade

**Request:**
```bash
POST /api/paper-reviews/42/add-grade
Content-Type: application/json
Authorization: Bearer {reviewer_token}

{
  "grading_criteria_id": 1,
  "score": 8.5,
  "notes": "Novel approach with solid methodology"
}
```

**Response (200):**
```json
{
  "message": "Grade saved",
  "grade": {
    "id": 101,
    "paper_review_id": 42,
    "grading_criteria_id": 1,
    "score": 8.5,
    "notes": "Novel approach with solid methodology",
    "criteria": {
      "id": 1,
      "name": "Originality",
      "max_score": 10
    }
  }
}
```

### Example 6: Reviewer Submits Final Review

**Request:**
```bash
POST /api/paper-reviews/42/submit-review
Content-Type: application/json
Authorization: Bearer {reviewer_token}

{
  "recommendation": "Accept. The paper presents a novel approach to time series prediction using attention mechanisms. The methodology is sound, experiments are comprehensive, and the results are promising. Minor revisions recommended for clarity in section 3.",
  "recommend_accept": true
}
```

**Response (200):**
```json
{
  "message": "Review submitted",
  "review": {
    "id": 42,
    "status": "completed",
    "total_score": 42.5,
    "recommendation": "Accept. The paper presents...",
    "recommend_accept": true,
    "review_date": "2026-03-08T14:30:00Z",
    "grades": [
      {"criteria_id": 1, "criteria_name": "Originality", "score": 8.5},
      {"criteria_id": 2, "criteria_name": "Methodology", "score": 9.0},
      {"criteria_id": 3, "criteria_name": "Clarity", "score": 8.0},
      {"criteria_id": 4, "criteria_name": "Significance", "score": 8.5},
      {"criteria_id": 5, "criteria_name": "Relevance", "score": 8.0}
    ]
  }
}
```

### Example 7: Get Submission With All Reviews (Admin)

**Request:**
```bash
GET /api/submissions/15/reviews
Authorization: Bearer {admin_token}
```

**Response (200):**
```json
{
  "submission": {
    "id": 15,
    "title": "Deep Learning for Time Series...",
    "presenter_name": "John Doe",
    "status": "submitted"
  },
  "reviews": [
    {
      "id": 42,
      "reviewer": {"id": 5, "name": "Dr. Sarah Johnson"},
      "status": "completed",
      "total_score": 42.5,
      "recommend_accept": true,
      "recommendation": "Accept...",
      "grades": [
        {"criteria_id": 1, "score": 8.5},
        {"criteria_id": 2, "score": 9.0}
      ]
    },
    {
      "id": 43,
      "reviewer": {"id": 6, "name": "Prof. Mike Chen"},
      "status": "in_progress",
      "total_score": null,
      "recommend_accept": null
    }
  ],
  "statistics": {
    "total_reviews": 2,
    "average_score": 42.5,
    "accept_count": 1,
    "reject_count": 0,
    "pending_count": 1
  }
}
```

### Example 8: Get Conference Topics

**Request:**
```bash
GET /api/conferences/1/topics
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "conference_id": 1,
  "conference_name": "AI Conference 2026",
  "count": 5,
  "topics": [
    {"id": 1, "name": "Artificial Intelligence", "order": 1},
    {"id": 3, "name": "Deep Learning", "order": 2},
    {"id": 2, "name": "Machine Learning", "order": 3},
    {"id": 4, "name": "Computer Vision", "order": 4},
    {"id": 5, "name": "NLP", "order": 5}
  ]
}
```

### Example 9: Get Grading Criteria With Stats

**Request:**
```bash
GET /api/conferences/1/grading-criteria?include_stats=1
Authorization: Bearer {admin_token}
```

**Response (200):**
```json
{
  "conference_id": 1,
  "conference_name": "AI Conference 2026",
  "total_max_score": 50,
  "count": 5,
  "criteria": [
    {
      "id": 1,
      "name": "Originality",
      "description": "Is the work novel?",
      "max_score": 10,
      "order": 1
    }
  ],
  "statistics": {
    "1": {
      "name": "Originality",
      "total_grades": 24,
      "average_score": 7.92,
      "highest_score": 10,
      "lowest_score": 5
    }
  }
}
```

### Example 10: Get Reviewer Statistics

**Request:**
```bash
GET /api/reviewer/statistics
Authorization: Bearer {reviewer_token}
```

**Response (200):**
```json
{
  "total_assigned": 8,
  "completed": 6,
  "in_progress": 2,
  "pending": 0,
  "average_score": 41.2,
  "recommendations": {
    "accept": 4,
    "reject": 2,
    "pending": 0
  }
}
```

---

## Authorization & Security

### Middleware Applied
```php
// No auth required
GET /api/topics
GET /api/topics/{id}

// Authenticated users only
POST /api/reviewer-applications
GET /api/reviewer/applications
PUT /api/paper-reviews/{id}

// Reviewer only
POST /api/paper-reviews/{id}/start-review
POST /api/paper-reviews/{id}/add-grade
POST /api/paper-reviews/{id}/submit-review

// Admin only
POST /api/reviewer-applications/{id}/approve
POST /api/reviewer-applications/{id}/reject
POST /api/paper-reviews
DELETE /api/paper-reviews/{id}
POST /api/topics
```

### Authorization Checks
- ReviewerApplicationController checks user ownership
- PaperReviewController checks reviewer assignment
- ReviewGradeController validates reviewer ownership
- All admin routes require `isAdmin()` check

---

## Error Responses

### 409 Conflict
```json
{
  "message": "Review assignment already exists",
  "review_id": 42
}
```

### 422 Validation Error
```json
{
  "message": "All 5 criteria must be graded",
  "graded": 3,
  "total": 5
}
```

### 403 Unauthorized
```json
{
  "message": "Unauthorized"
}
```

### 404 Not Found
```json
{
  "message": "Criteria not found in this conference"
}
```

---

## Query Parameters

### Pagination
```
?per_page=20   - Results per page
?page=2        - Page number
```

### Filtering
```
?status=pending        - Filter by status
?conference_id=1       - Filter by conference
?search=keyword        - Text search
?recommend_accept=true - Filter by recommendation
?include_stats=1       - Include statistics
```

### Sorting
```
orderBy('created_at', 'desc')   - Latest first
orderBy('order')                - Order field
orderBy('name')                 - Name alphabetically
```

---

## Next Steps

1. Integrate with React frontend
2. Create request/response interceptors
3. Add error handling UI components
4. Implement loading states
5. Add pagination UI components
6. Create forms for submission/review
7. Build admin dashboard
