@extends('layouts.index')
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">{{ $title }}</h3>
                <div>
                    <a href="{{ route('berita.index') }}" class="btn btn-secondary btn-sm me-2"><i class="fas fa-arrow-left"></i> Back</a>
                    <button type="submit" form="articleForm" class="btn btn-dark btn-sm">
                        <i class="fas fa-save"></i> Publish
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>Error!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
                    @csrf

                    <!-- Article Title -->
                    <div class="form-group mb-4">
                        <label for="judul_berita" class="form-label fw-semibold">Article Title <span class="text-danger">*</span></label>
                        <input type="text" 
                               id="judul_berita"
                               name="judul_berita" 
                               class="form-control form-control-lg" 
                               placeholder="Enter an engaging article title..."
                               value="{{ old('judul_berita') }}"
                               maxlength="100"
                               required>
                        <div class="form-text">
                            <small class="text-muted">
                                <span id="titleCount">0</span>/100 characters | 
                                <span class="text-info">💡 Tip: Use question format for GEO (How to...?, What is...?)</span>
                            </small>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="form-group mb-4">
                        <label for="gambar_berita" class="form-label fw-semibold">Featured Image <span class="text-danger">*</span></label>
                        <input type="file" 
                               id="gambar_berita" 
                               name="gambar_berita" 
                               class="form-control" 
                               accept="image/*" 
                               required>
                        <img class="d-none w-25 h-25 my-2" id="previewImg" src="#" alt="Preview image">
                    </div>

                    <!-- Article Content -->
                    <div class="form-group mb-4">
                        <label for="isi_berita" class="form-label fw-semibold">Article Content <span class="text-danger">*</span></label>
                        <textarea name="isi_berita" 
                                  id="editor" 
                                  class="form-control" 
                                  style="min-height: 500px;"
                                  required>{{ old('isi_berita') }}</textarea>
                        <div class="form-text">
                            <small class="text-muted">
                                💡 GEO Tips: Use H2/H3 headers, lists, tables. Min 1500 words for better AI visibility.
                            </small>
                        </div>
                    </div>

                    <!-- Featured Snippet / Key Takeaways (GEO) -->
                    <div class="form-group mb-4">
                        <label for="featured_snippet" class="form-label fw-semibold">
                            Key Takeaways / Featured Snippet 
                            <span class="badge bg-success">GEO</span>
                        </label>
                        <textarea name="featured_snippet" 
                                  id="featured_snippet"
                                  class="form-control" 
                                  rows="3"
                                  placeholder="Write a direct answer to the main question (40-60 words)..."
                                  maxlength="300">{{ old('featured_snippet') }}</textarea>
                        <div class="form-text">
                            <small class="text-muted">
                                <span id="snippetCount">0</span>/300 characters | 
                                <span class="text-info">This appears as "Key Takeaways" box for AI engines</span>
                            </small>
                        </div>
                    </div>

                    <!-- Conclusion / Quick Answer (GEO) -->
                    <div class="form-group mb-4">
                        <label for="conclusion" class="form-label fw-semibold">
                            Conclusion / Quick Answer
                            <span class="badge bg-success">GEO</span>
                        </label>
                        <textarea name="conclusion" 
                                  id="conclusion"
                                  class="form-control" 
                                  rows="4"
                                  placeholder="Summarize the main points and provide actionable takeaways..."
                                  maxlength="500">{{ old('conclusion') }}</textarea>
                        <div class="form-text">
                            <small class="text-muted">
                                <span id="conclusionCount">0</span>/500 characters | 
                                <span class="text-info">Direct answer for AI to cite</span>
                            </small>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Related Articles dengan Autocomplete seperti YouTube -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-link"></i> Related Articles
                    <span class="badge bg-info">SEO</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="position-relative">
                    <!-- Selected Articles Display -->
                    <div id="selectedArticles" class="mb-3">
                        <!-- Selected articles will appear here as tags -->
                    </div>
                    
                    <!-- Search Input -->
                    <input type="text" 
                           id="relatedArticleSearch"
                           class="form-control" 
                           placeholder="Type to search articles..."
                           autocomplete="off">
                    
                    <!-- Autocomplete Dropdown -->
                    <div id="articleSuggestions" class="position-absolute w-100 bg-white border rounded-bottom shadow-sm" 
                         style="display: none; max-height: 200px; overflow-y: auto; z-index: 1000; top: 100%;">
                        <!-- Suggestions will appear here -->
                    </div>
                </div>
                
                <!-- Hidden inputs for selected articles -->
                <div id="hiddenRelatedInputs"></div>
                
                <div class="form-text mt-2">
                    <small class="text-muted">
                        Start typing to search articles. Select related articles for topic clustering.
                    </small>
                </div>
            </div>
        </div>

        <!-- Tags dengan Autocomplete seperti YouTube -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tags"></i> Tags
                    <span class="badge bg-primary">SEO</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="position-relative">
                    <!-- Selected Tags Display -->
                    <div id="selectedTags" class="mb-3">
                        <!-- Selected tags will appear here -->
                    </div>
                    
                    <!-- Tag Input -->
                    <input type="text" 
                           id="tagInput"
                           class="form-control" 
                           placeholder="Add tags (press Enter or comma to add)..."
                           autocomplete="off">
                    
                    <!-- Hidden input for tags -->
                    <input type="hidden" name="tags" id="tagsHidden" form="articleForm">
                </div>
                
                <div class="form-text mt-2">
                    <small class="text-muted">
                        Press Enter or comma to add tags. Click X to remove.
                    </small>
                </div>
            </div>
        </div>

        <!-- FAQ Section (GEO) -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-question-circle"></i> FAQ Section 
                    <span class="badge bg-success">GEO</span>
                </h5>
            </div>
            <div class="card-body">
                <div id="faq-container">
                    <div class="faq-item mb-3">
                        <input type="text" 
                               name="faq_questions[]" 
                               class="form-control mb-2" 
                               placeholder="Question 1..."
                               form="articleForm">
                        <textarea name="faq_answers[]" 
                                  class="form-control" 
                                  rows="2" 
                                  placeholder="Answer 1..."
                                  form="articleForm"></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFAQ()">
                    <i class="fas fa-plus"></i> Add FAQ
                </button>
                <div class="form-text mt-2">
                    <small class="text-muted">
                        💡 Add 3-5 FAQs for better AI understanding
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Publish Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Publish Settings</h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="kategori_berita" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                    <select name="kategori_berita" id="kategori_berita" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="AI & Tech" {{ old('kategori_berita') == 'AI & Tech' ? 'selected' : '' }}>AI & Technology</option>
                        <option value="Web Development" {{ old('kategori_berita') == 'Web Development' ? 'selected' : '' }}>Web Development</option>
                        <option value="Tutorial" {{ old('kategori_berita') == 'Tutorial' ? 'selected' : '' }}>Tutorial</option>
                        <option value="Industry News" {{ old('kategori_berita') == 'Industry News' ? 'selected' : '' }}>Industry News</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="is_featured" class="form-check-label fw-semibold">Featured Article</label>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="tanggal_berita" class="form-label">Publish Date</label>
                    <input type="datetime-local" 
                           name="tanggal_berita" 
                           id="tanggal_berita"
                           class="form-control" 
                           value="{{ old('tanggal_berita', date('Y-m-d\TH:i')) }}">
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-search"></i> SEO Settings
                    <span class="badge bg-primary">SEO</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="meta_title" class="form-label fw-semibold">Meta Title</label>
                    <input type="text" 
                           name="meta_title" 
                           id="meta_title"
                           class="form-control" 
                           placeholder="Custom title for search engines"
                           value="{{ old('meta_title') }}"
                           maxlength="60">
                    <div class="form-text">
                        <small class="text-muted"><span id="metaTitleCount">0</span>/60 characters</small>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="meta_description" class="form-label fw-semibold">Meta Description</label>
                    <textarea name="meta_description" 
                              id="meta_description"
                              class="form-control" 
                              rows="3"
                              placeholder="Description for search engines"
                              maxlength="160">{{ old('meta_description') }}</textarea>
                    <div class="form-text">
                        <small class="text-muted"><span id="metaDescCount">0</span>/160 characters</small>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="focus_keyword" class="form-label fw-semibold">Focus Keyword</label>
                    <input type="text" 
                           name="focus_keyword" 
                           id="focus_keyword"
                           class="form-control" 
                           placeholder="Main keyword to target"
                           value="{{ old('focus_keyword') }}">
                    <small class="form-text text-muted">Primary keyword for SEO optimization</small>
                </div>
            </div>
        </div>

        <!-- GEO Optimization Score -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-robot"></i> SEO + GEO Score
                </h5>
            </div>
            <div class="card-body">
                <div class="progress mb-3" style="height: 25px;">
                    <div id="geoScore" class="progress-bar bg-success" role="progressbar" style="width: 0%">0%</div>
                </div>
                <ul class="list-unstyled" id="geoChecklist">
                    <li id="check-title">❌ Title (question format preferred)</li>
                    <li id="check-content">❌ Content (min 1500 words)</li>
                    <li id="check-snippet">❌ Key Takeaways added</li>
                    <li id="check-conclusion">❌ Conclusion added</li>
                    <li id="check-faq">❌ FAQ section (3+ questions)</li>
                    <li id="check-meta">❌ Meta description added</li>
                    <li id="check-tags">❌ Tags added</li>
                    <li id="check-keyword">❌ Focus keyword set</li>
                </ul>
                <div class="alert alert-info">
                    <small>💡 Aim for 80%+ score for optimal SEO + GEO</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles for tags -->
<style>
.article-tag, .tag-item {
    display: inline-block;
    padding: 5px 10px;
    margin: 3px;
    background: #e9ecef;
    border-radius: 20px;
    font-size: 14px;
}

.article-tag {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

.tag-item {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
}

.remove-tag, .remove-article {
    cursor: pointer;
    margin-left: 5px;
    color: #dc3545;
    font-weight: bold;
}

.suggestion-item {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.suggestion-item:hover {
    background: #f8f9fa;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-category {
    font-size: 12px;
    color: #6c757d;
}
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
// Initialize CKEditor
ClassicEditor
    .create(document.querySelector('#editor'), {
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ]
        }
    })
    .then(editor => {
        window.editor = editor;
        
        // Track content changes for GEO score
        editor.model.document.on('change:data', () => {
            updateGeoScore();
        });
    })
    .catch(error => console.error(error));

// Related Articles Autocomplete System
let selectedArticles = [];
let searchTimeout;

document.getElementById('relatedArticleSearch').addEventListener('input', function() {
    const query = this.value;
    clearTimeout(searchTimeout);
    
    if (query.length < 3) {
        document.getElementById('articleSuggestions').style.display = 'none';
        return;
    }
    
    // Debounce search
    searchTimeout = setTimeout(() => {
        searchArticles(query);
    }, 300);
});

function searchArticles(query) {
    // AJAX call to search articles
    fetch(`/api/articles/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displayArticleSuggestions(data);
        })
        .catch(() => {
            // Fallback for demo - simulate article search
            const demoArticles = [
                {id: 1, title: 'How to Implement AI in Business', category: 'AI & Tech'},
                {id: 2, title: 'Web Development Best Practices', category: 'Web Development'},
                {id: 3, title: 'Machine Learning Tutorial', category: 'Tutorial'},
            ].filter(article => 
                article.title.toLowerCase().includes(query.toLowerCase())
            );
            displayArticleSuggestions(demoArticles);
        });
}

function displayArticleSuggestions(articles) {
    const suggestionsDiv = document.getElementById('articleSuggestions');
    
    if (articles.length === 0) {
        suggestionsDiv.innerHTML = '<div class="p-3 text-muted">No articles found</div>';
        suggestionsDiv.style.display = 'block';
        return;
    }
    
    suggestionsDiv.innerHTML = articles.map(article => `
        <div class="suggestion-item" onclick="selectArticle(${article.id}, '${article.title.replace(/'/g, "\\'")}', '${article.category}')">
            <div>${article.title}</div>
            <div class="suggestion-category">${article.category}</div>
        </div>
    `).join('');
    
    suggestionsDiv.style.display = 'block';
}

function selectArticle(id, title, category) {
    // Check if already selected
    if (selectedArticles.find(a => a.id === id)) {
        return;
    }
    
    selectedArticles.push({id, title, category});
    updateSelectedArticlesDisplay();
    
    // Clear search
    document.getElementById('relatedArticleSearch').value = '';
    document.getElementById('articleSuggestions').style.display = 'none';
}

function updateSelectedArticlesDisplay() {
    const container = document.getElementById('selectedArticles');
    const hiddenInputs = document.getElementById('hiddenRelatedInputs');
    
    container.innerHTML = selectedArticles.map(article => `
        <div class="article-tag">
            ${article.title}
            <span class="remove-article" onclick="removeArticle(${article.id})">×</span>
        </div>
    `).join('');
    
    hiddenInputs.innerHTML = selectedArticles.map(article => 
        `<input type="hidden" name="related_ids[]" value="${article.id}" form="articleForm">`
    ).join('');
}

function removeArticle(id) {
    selectedArticles = selectedArticles.filter(a => a.id !== id);
    updateSelectedArticlesDisplay();
}

// Tags System (YouTube-style)
let tags = [];

document.getElementById('tagInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        const tag = this.value.trim();
        if (tag && !tags.includes(tag)) {
            tags.push(tag);
            updateTagsDisplay();
            this.value = '';
        }
    }
});

function updateTagsDisplay() {
    const container = document.getElementById('selectedTags');
    const hiddenInput = document.getElementById('tagsHidden');
    
    container.innerHTML = tags.map(tag => `
        <div class="tag-item">
            #${tag}
            <span class="remove-tag" onclick="removeTag('${tag.replace(/'/g, "\\'")}')">×</span>
        </div>
    `).join('');
    
    hiddenInput.value = tags.join(', ');
    updateGeoScore();
}

function removeTag(tag) {
    tags = tags.filter(t => t !== tag);
    updateTagsDisplay();
}

// Hide suggestions when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#relatedArticleSearch') && !e.target.closest('#articleSuggestions')) {
        document.getElementById('articleSuggestions').style.display = 'none';
    }
});

// Character counters
const setupCounter = (inputId, countId) => {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(countId);
    
    if (input && counter) {
        const updateCounter = () => {
            counter.textContent = input.value.length;
            updateGeoScore();
        };
        input.addEventListener('input', updateCounter);
        updateCounter();
    }
};

setupCounter('judul_berita', 'titleCount');
setupCounter('featured_snippet', 'snippetCount');
setupCounter('meta_title', 'metaTitleCount');
setupCounter('meta_description', 'metaDescCount');
setupCounter('conclusion', 'conclusionCount');

// Add FAQ functionality
let faqCount = 1;
function addFAQ() {
    faqCount++;
    const container = document.getElementById('faq-container');
    const newFaq = document.createElement('div');
    newFaq.className = 'faq-item mb-3';
    newFaq.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label mb-0">Question ${faqCount}</label>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.parentElement.remove(); updateGeoScore();">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <input type="text" 
               name="faq_questions[]" 
               class="form-control mb-2" 
               placeholder="Question ${faqCount}..."
               form="articleForm"
               onchange="updateGeoScore()">
        <textarea name="faq_answers[]" 
                  class="form-control" 
                  rows="2" 
                  placeholder="Answer ${faqCount}..."
                  form="articleForm"
                  onchange="updateGeoScore()"></textarea>
    `;
    container.appendChild(newFaq);
    updateGeoScore();
}

// Process FAQ data before submit
document.getElementById('articleForm').addEventListener('submit', function(e) {
    const questions = document.getElementsByName('faq_questions[]');
    const answers = document.getElementsByName('faq_answers[]');
    const faqData = [];
    
    for (let i = 0; i < questions.length; i++) {
        if (questions[i].value && answers[i].value) {
            faqData.push({
                question: questions[i].value,
                answer: answers[i].value
            });
        }
    }
    
    // Add hidden input with JSON data
    const faqInput = document.createElement('input');
    faqInput.type = 'hidden';
    faqInput.name = 'faq_data';
    faqInput.value = JSON.stringify(faqData);
    this.appendChild(faqInput);
});

// SEO + GEO Score Calculator
function updateGeoScore() {
    let score = 0;
    const checks = {
        title: false,
        content: false,
        snippet: false,
        conclusion: false,
        faq: false,
        meta: false,
        tags: false,
        keyword: false
    };
    
    // Check title (prefer question format)
    const title = document.getElementById('judul_berita').value;
    if (title.length > 20) {
        checks.title = true;
        score += 10;
        if (title.includes('?') || title.toLowerCase().includes('how') || title.toLowerCase().includes('what')) {
            score += 5;
        }
    }
    
    // Check content length
    if (window.editor) {
        const content = window.editor.getData();
        const wordCount = content.split(' ').length;
        if (wordCount > 500) {
            checks.content = true;
            score += 15;
            if (wordCount > 1500) {
                score += 10;
            }
        }
    }
    
    // Check featured snippet
    if (document.getElementById('featured_snippet').value.length > 40) {
        checks.snippet = true;
        score += 15;
    }
    
    // Check conclusion
    if (document.getElementById('conclusion').value.length > 50) {
        checks.conclusion = true;
        score += 15;
    }
    
    // Check FAQ
    const faqs = document.getElementsByName('faq_questions[]');
    let validFaqs = 0;
    for (let faq of faqs) {
        if (faq.value.length > 5) validFaqs++;
    }
    if (validFaqs >= 3) {
        checks.faq = true;
        score += 15;
    }
    
    // Check meta description
    if (document.getElementById('meta_description').value.length > 50) {
        checks.meta = true;
        score += 10;
    }
    
    // Check tags
    if (tags.length >= 3) {
        checks.tags = true;
        score += 10;
    }
    
    // Check focus keyword
    if (document.getElementById('focus_keyword').value.length > 3) {
        checks.keyword = true;
        score += 10;
    }
    
    // Update UI
    document.getElementById('geoScore').style.width = score + '%';
    document.getElementById('geoScore').textContent = score + '%';
    
    // Update color based on score
    const progressBar = document.getElementById('geoScore');
    if (score >= 80) {
        progressBar.className = 'progress-bar bg-success';
    } else if (score >= 60) {
        progressBar.className = 'progress-bar bg-warning';
    } else {
        progressBar.className = 'progress-bar bg-danger';
    }
    
    // Update checklist
    document.getElementById('check-title').innerHTML = checks.title ? '✅ Title (question format preferred)' : '❌ Title (question format preferred)';
    document.getElementById('check-content').innerHTML = checks.content ? '✅ Content (min 1500 words)' : '❌ Content (min 1500 words)';
    document.getElementById('check-snippet').innerHTML = checks.snippet ? '✅ Key Takeaways added' : '❌ Key Takeaways added';
    document.getElementById('check-conclusion').innerHTML = checks.conclusion ? '✅ Conclusion added' : '❌ Conclusion added';
    document.getElementById('check-faq').innerHTML = checks.faq ? '✅ FAQ section (3+ questions)' : '❌ FAQ section (3+ questions)';
    document.getElementById('check-meta').innerHTML = checks.meta ? '✅ Meta description added' : '❌ Meta description added';
    document.getElementById('check-tags').innerHTML = checks.tags ? '✅ Tags added' : '❌ Tags added';
    document.getElementById('check-keyword').innerHTML = checks.keyword ? '✅ Focus keyword set' : '❌ Focus keyword set';
}

// Image preview
document.getElementById('gambar_berita').addEventListener('change', function() {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').setAttribute('src', e.target.result);
            document.getElementById('previewImg').classList.remove("d-none");
            document.getElementById('previewImg').classList.add("d-block");
        };
        reader.readAsDataURL(input.files[0]);
    }
});

// Initialize score on load
window.addEventListener('load', () => {
    updateGeoScore();
});
</script>
@endsection