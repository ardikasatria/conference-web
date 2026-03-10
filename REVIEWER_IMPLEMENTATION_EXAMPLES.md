# Reviewer System - Implementation Examples

## Example 1: User Applies to Become Reviewer

### Frontend (Form Component)
```jsx
// resources/js/components/ReviewerApplicationForm.jsx
import { useState } from 'react';

export default function ReviewerApplicationForm({ conference }) {
  const [formData, setFormData] = useState({
    motivation: '',
    expertise: '',
    field_of_study: '',
    sub_field: '',
    selected_topics: [],
    full_name_with_degree: '',
    affiliation: '',
  });

  const [topics, setTopics] = useState([]);

  // Load available topics
  React.useEffect(() => {
    fetch(`/api/conferences/${conference.id}/topics`)
      .then(res => res.json())
      .then(data => setTopics(data))
      .catch(err => console.error(err));
  }, [conference.id]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    const response = await fetch('/api/reviewer-applications', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        conference_id: conference.id,
        ...formData,
      }),
    });

    if (response.ok) {
      alert('Application submitted! Waiting for admin approval.');
    }
  };

  const handleTopicChange = (topicId) => {
    setFormData(prev => ({
      ...prev,
      selected_topics: prev.selected_topics.includes(topicId)
        ? prev.selected_topics.filter(id => id !== topicId)
        : [...prev.selected_topics, topicId],
    }));
  };

  return (
    <form onSubmit={handleSubmit} className="p-6 bg-white rounded-lg shadow">
      <h2 className="text-2xl font-bold mb-4">Apply as Reviewer</h2>

      {/* Full Name with Degree */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Full Name with Degree</label>
        <input
          type="text"
          placeholder="e.g., Dr. John Smith, PhD"
          value={formData.full_name_with_degree}
          onChange={(e) => setFormData({...formData, full_name_with_degree: e.target.value})}
          className="w-full px-4 py-2 border rounded-lg"
          required
        />
      </div>

      {/* Affiliation */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Affiliation</label>
        <input
          type="text"
          placeholder="e.g., Stanford University, MIT"
          value={formData.affiliation}
          onChange={(e) => setFormData({...formData, affiliation: e.target.value})}
          className="w-full px-4 py-2 border rounded-lg"
          required
        />
      </div>

      {/* Field of Study */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Field of Study</label>
        <input
          type="text"
          placeholder="e.g., Computer Science"
          value={formData.field_of_study}
          onChange={(e) => setFormData({...formData, field_of_study: e.target.value})}
          className="w-full px-4 py-2 border rounded-lg"
          required
        />
      </div>

      {/* Sub-field */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Sub-field</label>
        <input
          type="text"
          placeholder="e.g., Machine Learning"
          value={formData.sub_field}
          onChange={(e) => setFormData({...formData, sub_field: e.target.value})}
          className="w-full px-4 py-2 border rounded-lg"
          required
        />
      </div>

      {/* Selected Topics */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Topics of Interest</label>
        <div className="space-y-2">
          {topics.map(topic => (
            <label key={topic.id} className="flex items-center">
              <input
                type="checkbox"
                checked={formData.selected_topics.includes(topic.id)}
                onChange={() => handleTopicChange(topic.id)}
                className="mr-2"
              />
              <span>{topic.name}</span>
            </label>
          ))}
        </div>
      </div>

      {/* Expertise */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Areas of Expertise</label>
        <textarea
          rows="4"
          placeholder="List your research interests and expertise..."
          value={formData.expertise}
          onChange={(e) => setFormData({...formData, expertise: e.target.value})}
          className="w-full px-4 py-2 border rounded-lg"
          required
        />
      </div>

      {/* Motivation */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Why do you want to be a reviewer?</label>
        <textarea
          rows="4"
          placeholder="Explain your motivation..."
          value={formData.motivation}
          onChange={(e) => setFormData({...formData, motivation: e.target.value})}
          className="w-full px-4 py-2 border rounded-lg"
          required
        />
      </div>

      <button type="submit" className="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
        Submit Application
      </button>
    </form>
  );
}
```

### Backend Controller
```php
// app/Http/Controllers/ReviewerApplicationController.php
namespace App\Http\Controllers;

use App\Models\ReviewerApplication;
use App\Models\Conference;
use Illuminate\Http\Request;

class ReviewerApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'motivation' => 'required|string|min:50',
            'expertise' => 'required|string|min:50',
            'field_of_study' => 'required|string',
            'sub_field' => 'required|string',
            'selected_topics' => 'array|required',
            'selected_topics.*' => 'exists:topics,id',
            'full_name_with_degree' => 'required|string',
            'affiliation' => 'required|string',
        ]);

        // Create application
        $application = ReviewerApplication::create([
            'user_id' => auth()->id(),
            ...$validated,
            'status' => 'pending',
        ]);

        // Sync topics
        $application->syncTopics($validated['selected_topics']);

        return response()->json([
            'message' => 'Application submitted successfully',
            'application' => $application,
        ], 201);
    }
}
```

---

## Example 2: Admin Approves Reviewer Application

### Admin Dashboard Component
```jsx
// resources/js/pages/Admin/ReviewerApplications.jsx
import { useState, useEffect } from 'react';

export default function ReviewerApplications({ conference }) {
  const [applications, setApplications] = useState([]);

  useEffect(() => {
    // Load pending applications
    fetch(`/api/conferences/${conference.id}/reviewer-applications?status=pending`)
      .then(res => res.json())
      .then(data => setApplications(data))
      .catch(err => console.error(err));
  }, [conference.id]);

  const handleApprove = async (appId) => {
    const notes = prompt('Admin notes (optional):');
    
    const response = await fetch(`/api/reviewer-applications/${appId}/approve`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ notes }),
    });

    if (response.ok) {
      alert('Approved! User now has reviewer role.');
      setApplications(applications.filter(a => a.id !== appId));
    }
  };

  const handleReject = async (appId) => {
    const notes = prompt('Rejection reason:');
    
    const response = await fetch(`/api/reviewer-applications/${appId}/reject`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ notes }),
    });

    if (response.ok) {
      alert('Rejected.');
      setApplications(applications.filter(a => a.id !== appId));
    }
  };

  return (
    <div className="p-6">
      <h1 className="text-3xl font-bold mb-6">Pending Reviewer Applications</h1>

      <table className="w-full border-collapse">
        <thead className="bg-gray-100">
          <tr>
            <th className="border p-3 text-left">Name</th>
            <th className="border p-3 text-left">Affiliation</th>
            <th className="border p-3 text-left">Field</th>
            <th className="border p-3 text-left">Topics</th>
            <th className="border p-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          {applications.map(app => (
            <tr key={app.id} className="hover:bg-gray-50">
              <td className="border p-3">{app.full_name_with_degree}</td>
              <td className="border p-3">{app.affiliation}</td>
              <td className="border p-3">{app.field_of_study}</td>
              <td className="border p-3">
                {app.topics?.map(t => t.name).join(', ')}
              </td>
              <td className="border p-3">
                <button
                  onClick={() => handleApprove(app.id)}
                  className="px-3 py-1 bg-green-600 text-white rounded mr-2"
                >
                  Approve
                </button>
                <button
                  onClick={() => handleReject(app.id)}
                  className="px-3 py-1 bg-red-600 text-white rounded"
                >
                  Reject
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
```

### Backend Approve Logic
```php
// app/Http/Controllers/ReviewerApplicationController.php
public function approve(ReviewerApplication $application, Request $request)
{
    $application->approve(auth()->id(), $request->input('notes'));

    // Optionally, sync topics to user profile
    // (if you want reviewer to inherit topics from application)

    return response()->json([
        'message' => 'Application approved',
        'status' => $application->status,
    ]);
}

public function reject(ReviewerApplication $application, Request $request)
{
    $application->reject(auth()->id(), $request->input('notes'));

    return response()->json([
        'message' => 'Application rejected',
    ]);
}
```

---

## Example 3: Reviewer Reviews a Paper

### Paper Review Form Component
```jsx
// resources/js/components/PaperReviewForm.jsx
import { useState, useEffect } from 'react';

export default function PaperReviewForm({ review, submission, conference }) {
  const [grades, setGrades] = useState({});
  const [questions, setQuestions] = useState({});
  const [recommendation, setRecommendation] = useState('');
  const [recommendAccept, setRecommendAccept] = useState(null);
  const [criteria, setCriteria] = useState([]);
  const [reviewQuestions, setReviewQuestions] = useState([]);

  useEffect(() => {
    // Load grading criteria
    fetch(`/api/conferences/${conference.id}/grading-criteria`)
      .then(res => res.json())
      .then(data => {
        setCriteria(data);
        // Initialize grades object
        const initial = {};
        data.forEach(c => {
          initial[c.id] = review.grades?.find(g => g.grading_criteria_id === c.id)?.score || '';
        });
        setGrades(initial);
      });

    // Load review questions
    if (review.id) {
      fetch(`/api/paper-reviews/${review.id}/questions`)
        .then(res => res.json())
        .then(data => {
          setReviewQuestions(data);
          const initial = {};
          data.forEach(q => {
            initial[q.id] = q.answer || '';
          });
          setQuestions(initial);
        });
    }
  }, [conference.id, review.id]);

  const handleSubmitReview = async (e) => {
    e.preventDefault();

    // Add/update grades
    for (const [criteriaId, score] of Object.entries(grades)) {
      if (score) {
        await fetch(`/api/paper-reviews/${review.id}/add-grade`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ grading_criteria_id: criteriaId, score }),
        });
      }
    }

    // Update questions
    for (const [questionId, answer] of Object.entries(questions)) {
      if (answer) {
        await fetch(`/api/review-questions/${questionId}/update-answer`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ answer }),
        });
      }
    }

    // Submit review
    const response = await fetch(`/api/paper-reviews/${review.id}/submit-review`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        recommendation,
        recommend_accept: recommendAccept,
      }),
    });

    if (response.ok) {
      alert('Review submitted!');
    }
  };

  return (
    <form onSubmit={handleSubmitReview} className="p-6 bg-white rounded-lg shadow">
      <h2 className="text-2xl font-bold mb-4">Review Paper: {submission.title}</h2>

      {/* Paper Info */}
      <div className="mb-6 p-4 bg-gray-100 rounded">
        <p><strong>Author:</strong> {submission.presenter_name}</p>
        <a href={submission.file_path} target="_blank" className="text-blue-600 underline">
          Download PDF
        </a>
      </div>

      {/* Grading Criteria */}
      <div className="mb-8">
        <h3 className="text-xl font-bold mb-4">Grading Criteria</h3>
        <div className="space-y-6">
          {criteria.map(c => (
            <div key={c.id} className="border rounded p-4">
              <label className="block font-semibold mb-2">
                {c.name} (max: {c.max_score})
              </label>
              <p className="text-gray-600 mb-3">{c.description}</p>
              <input
                type="number"
                min="0"
                max={c.max_score}
                step="0.5"
                value={grades[c.id]}
                onChange={(e) => setGrades({...grades, [c.id]: e.target.value})}
                placeholder="Score"
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
          ))}
        </div>
      </div>

      {/* Q & A Section */}
      {reviewQuestions.length > 0 && (
        <div className="mb-8">
          <h3 className="text-xl font-bold mb-4">Questions & Feedback</h3>
          <div className="space-y-4">
            {reviewQuestions.map(q => (
              <div key={q.id} className="border rounded p-4">
                <p className="font-semibold mb-2">{q.question}</p>
                <textarea
                  rows="3"
                  value={questions[q.id] || ''}
                  onChange={(e) => setQuestions({...questions, [q.id]: e.target.value})}
                  placeholder="Your answer..."
                  className="w-full px-4 py-2 border rounded-lg"
                />
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Recommendation */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Recommendation</label>
        <select
          value={recommendAccept === null ? '' : (recommendAccept ? 'accept' : 'reject')}
          onChange={(e) => setRecommendAccept(e.target.value === 'accept' ? true : e.target.value === 'reject' ? false : null)}
          className="w-full px-4 py-2 border rounded-lg"
        >
          <option value="">Select...</option>
          <option value="accept">Accept</option>
          <option value="reject">Reject</option>
        </select>
      </div>

      {/* Comments */}
      <div className="mb-4">
        <label className="block font-semibold mb-2">Final Comments</label>
        <textarea
          rows="5"
          value={recommendation}
          onChange={(e) => setRecommendation(e.target.value)}
          placeholder="Overall comments and recommendation..."
          className="w-full px-4 py-2 border rounded-lg"
          required
        />
      </div>

      <button type="submit" className="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
        Submit Review
      </button>
    </form>
  );
}
```

### Backend PaperReview Controller
```php
// app/Http/Controllers/PaperReviewController.php
public function addGrade(PaperReview $review, Request $request)
{
    $validated = $request->validate([
        'grading_criteria_id' => 'required|exists:grading_criteria,id',
        'score' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ]);

    // Update or create grade
    $review->grades()
        ->updateOrCreate(
            ['grading_criteria_id' => $validated['grading_criteria_id']],
            ['score' => $validated['score'], 'notes' => $validated['notes'] ?? null]
        );

    return response()->json(['message' => 'Grade saved']);
}

public function submitReview(PaperReview $review, Request $request)
{
    $validated = $request->validate([
        'recommendation' => 'required|string',
        'recommend_accept' => 'nullable|boolean',
    ]);

    $review->submitReview(
        $validated['recommendation'],
        $validated['recommend_accept']
    );

    return response()->json([
        'message' => 'Review submitted',
        'review' => $review->fresh(['grades', 'questions']),
    ]);
}
```

---

## Example 4: Admin Views Paper Review Results

```php
// app/Http/Controllers/SubmissionController.php
public function showWithReviews(Submission $submission)
{
    $reviews = $submission->reviews()
        ->with(['reviewer', 'grades.criteria', 'questions'])
        ->get();

    $totalScore = $reviews->sum('total_score');
    $avgScore = $reviews->avg('total_score');
    $allRecommendAccept = $reviews->pluck('recommend_accept');
    
    $acceptCount = $allRecommendAccept->filter(fn($r) => $r === true)->count();
    $rejectCount = $allRecommendAccept->filter(fn($r) => $r === false)->count();
    $neutralCount = $allRecommendAccept->filter(fn($r) => $r === null)->count();

    return view('submissions.show', [
        'submission' => $submission,
        'reviews' => $reviews,
        'statistics' => [
            'totalScore' => $totalScore,
            'avgScore' => round($avgScore, 2),
            'acceptCount' => $acceptCount,
            'rejectCount' => $rejectCount,
            'neutralCount' => $neutralCount,
        ],
    ]);
}
```

---

## Database Query Examples

```php
// Get pending reviews
$pending = PaperReview::where('status', 'pending')
    ->with('submission', 'reviewer')
    ->get();

// Get completed reviews for a submission
$reviews = PaperReview::where('submission_id', $submissionId)
    ->with('grades.criteria', 'questions')
    ->get();

// Get all reviews from a specific reviewer
$myReviews = auth()->user()->paperReviewsAsReviewer();

// Get high-scoring papers
$excellent = PaperReview::where('total_score', '>=', 40)
    ->where('conference_id', $conferenceId)
    ->with('submission')
    ->get();

// Get topics for reviewer application dropdown
$topics = $conference->topics()->get();
```

---

## Next Steps

1. Create API routes in `routes/api.php`
2. Create remaining controllers
3. Add validation rules & error handling
4. Create React components for reviewer dashboard
5. Add email notifications
6. Implement file upload for paper review comments
