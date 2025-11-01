<?php
/**
 * Face Analysis Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="face-analysis-plugin">
    <div class="my-app-container" dir="ltr">
        <div class="face-analysis-container">
            <div class="face-analysis-card">
                <!-- Header -->
                <div class="face-analysis-header">
                    <div class="header-content">
                        <div class="title-section">
                            <h2 class="face-analysis-title" data-translate="title"><?php echo esc_html($atts['title']); ?></h2>
                            <p class="face-analysis-description" data-translate="description"><?php echo esc_html($atts['description']); ?></p>
                        </div>
                        <div class="lang-selector">
                            <button class="lang-selector-btn active" data-lang="en" data-translate="lang_en">EN</button>
                            <button class="lang-selector-btn" data-lang="fr" data-translate="lang_fr">FR</button>
                            <button class="lang-selector-btn" data-lang="ar" data-translate="lang_ar">AR</button>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="face-analysis-body">
                    <!-- Image Preview -->
                    <div class="face-analysis-preview">
                        <div class="preview-placeholder" id="preview-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="face-icon">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="8" cy="10" r="1"/>
                                <circle cx="16" cy="10" r="1"/>
                                <path d="M8 16s1.5 2 4 2 4-2 4-2"/>
                            </svg>
                            <p data-translate="upload_placeholder">Upload or capture an image</p>
                        </div>
                        <img id="preview-image" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='256' height='256'%3E%3C/svg%3E" alt="Uploaded Image Preview" width="256" height="256" class="preview-image" style="display: none;">
                    </div>

                    <!-- Webcam Section -->
                    <div id="webcam-section" class="webcam-section" style="display:none;">
                        <video id="webcam-video" width="256" height="256" autoplay playsinline></video>
                        <canvas id="webcam-canvas" width="256" height="256" style="display:none;"></canvas>
                    </div>

                    <!-- Upload Section -->
                    <div class="upload-section">
                        <input id="picture" type="file" class="hidden-file" accept="image/png, image/jpeg">
                        <button type="button" class="upload-button" onclick="document.getElementById('picture').click()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-upload">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" x2="12" y1="3" y2="15"/>
                            </svg>
                            <span data-translate="upload_button">Upload Image</span>
                        </button>

                        <button type="button" class="webcam-button" id="webcam-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                            <span data-translate="use_camera">Use Camera</span>
                        </button>

                        <button type="button" class="capture-button" id="capture-btn" style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span data-translate="capture_photo">Capture Photo</span>
                        </button>

                        <button type="button" class="stop-camera-button" id="stop-camera-btn" style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            </svg>
                            <span data-translate="stop_camera">Stop Camera</span>
                        </button>

                        <!-- GDPR Consent -->
                        <div class="gdpr-consent-section">
                            <div class="consent-checkbox">
                                <input type="checkbox" id="consent" required>
                                <label for="consent" data-translate="consent_text">I agree to the processing of my photo for temporary analysis to receive beauty tips. I understand that my photo will not be stored.</label>
                            </div>
                            <p class="privacy-policy-link">
                                <span data-translate="privacy_policy_text">Read our</span>
                                <a href="https://beautyai.duckdns.org/privacy-policy/" target="_blank" data-translate="privacy_policy_link">Privacy Policy</a>
                                <span data-translate="privacy_policy_details">for more details.</span>
                            </p>
                        </div>

                        <button class="analyze-button" disabled id="analyze-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 12l2 2 4-4"/>
                                <path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1"/>
                            </svg>
                            <span class="button-text" data-translate="analyze_face">Analyze Face</span>
                        </button>
                        <p class="upload-note" data-translate="upload_note">PNG, JPG, or JPEG up to 10MB</p>
                    </div>

                    <!-- Loading Section -->
                    <div id="loading-section" class="loading-section" style="display:none;">
                        <div class="loading-content">
                            <div class="loading-spinner">
                                <div class="spinner-ring"></div>
                                <div class="spinner-ring"></div>
                                <div class="spinner-ring"></div>
                            </div>
                            <div class="loading-text">
                                <h3 data-translate="analyzing_title">Analyzing Your Face</h3>
                                <p data-translate="analyzing_subtitle">Please wait while we process your image...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div id="results-section" class="results-section" style="display:none;">
                        <div class="results-header">
                            <div class="results-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 12l2 2 4-4"/>
                                    <path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1"/>
                                </svg>
                            </div>
                            <h2 data-translate="analysis_complete">Analysis Complete</h2>
                            <p data-translate="analysis_results_subtitle">Here are your personalized skin analysis results</p>
                        </div>

                        <div class="analysis-results">
                            <div class="face-display">
                                <div class="face-frame">
                                    <img id="analyzed-face" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3C/svg%3E" alt="Analyzed Face" width="80" height="80">
                                    <div class="face-badge">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 12l2 2 4-4"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="face-info">
                                    <h3 id="face-status" data-translate="face_detected">Face Detected Successfully</h3>
                                    <p id="analysis-summary" data-translate="analysis_confidence">Analysis completed with high confidence</p>
                                </div>
                            </div>

                            <!-- Result Cards -->
                            <div class="result-cards">
                                <div class="result-card" id="skin-type-card">
                                    <div class="card-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </div>
                                    <div class="card-content">
                                        <h4 data-translate="skin_type">Skin Type</h4>
                                        <p id="skin-type-value">-</p>
                                        <div class="probability-bars" id="skin-probabilities"></div>
                                    </div>
                                </div>

                                <div class="result-card" id="eye-colors-card">
                                    <div class="card-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="3"/>
                                            <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"/>
                                        </svg>
                                    </div>
                                    <div class="card-content">
                                        <h4 data-translate="eye_colors">Eye Colors</h4>
                                        <div class="eye-colors">
                                            <div class="eye-color">
                                                <span class="eye-label" data-translate="left_eye">Left:</span>
                                                <span id="left-eye-color" class="eye-value">-</span>
                                            </div>
                                            <div class="eye-color">
                                                <span class="eye-label" data-translate="right_eye">Right:</span>
                                                <span id="right-eye-color" class="eye-value">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="result-card" id="acne-card">
                                    <div class="card-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="card-content">
                                        <h4 data-translate="acne_analysis">Acne Analysis</h4>
                                        <div class="acne-info">
                                            <div class="acne-level">
                                                <span data-translate="level">Level:</span>
                                                <span id="acne-level" class="acne-value">-</span>
                                            </div>
                                            <div class="confidence-meter">
                                                <div class="confidence-bar">
                                                    <div id="acne-confidence-fill" class="confidence-fill"></div>
                                                </div>
                                                <span id="acne-confidence" class="confidence-text">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="result-card" id="detections-card">
                                    <div class="card-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 12l2 2 4-4"/>
                                            <path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1"/>
                                        </svg>
                                    </div>
                                    <div class="card-content">
                                        <h4 data-translate="detected_issues">Detected Issues</h4>
                                        <div id="detected-issues" class="detected-issues"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analysis Images Section -->
                    <div id="analysis-images" class="analysis-images-section" style="display:none;">
                        <div class="images-header">
                            <h3 data-translate="detailed_analysis">Detailed Analysis</h3>
                            <p data-translate="visual_breakdown">Visual breakdown of detected areas and skin conditions</p>
                        </div>
                        
                        <div class="analysis-images-grid">
                            <div class="analysis-image-card">
                                <div class="image-header">
                                    <div class="image-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 12l2 2 4-4"/>
                                            <path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1"/>
                                        </svg>
                                    </div>
                                    <div class="image-title">
                                        <h4 data-translate="issue_detection">Issue Detection</h4>
                                        <p data-translate="areas_highlighted">Areas of concern highlighted</p>
                                    </div>
                                </div>
                                <div class="image-container">
                                    <img id="yolo-annotated-image" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3C/svg%3E" alt="YOLO Detection Results" width="300" height="300" style="display: none;">
                                </div>
                            </div>

                            <div class="analysis-image-card">
                                <div class="image-header">
                                    <div class="image-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="3"/>
                                            <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"/>
                                        </svg>
                                    </div>
                                    <div class="image-title">
                                        <h4 data-translate="skin_segmentation">Skin Segmentation</h4>
                                        <p data-translate="skin_areas_mapped">Different skin areas mapped</p>
                                    </div>
                                </div>
                                <div class="image-container">
                                    <img id="segmentation-overlay-image" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3C/svg%3E" alt="Segmentation Analysis" width="300" height="300" style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Section -->
                    <div class="feedback-section" id="feedbackSection" style="display:none;">
                        <div class="feedback-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            <h3 data-translate="feedback_header">How was your experience?</h3>
                        </div>
                        <div class="feedback-buttons">
                            <button class="btn btn-feedback-positive" id="likeBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                </svg>
                                <span data-translate="feedback_helpful">Helpful</span>
                            </button>
                            <button class="btn btn-feedback-negative" id="dislikeBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/>
                                </svg>
                                <span data-translate="feedback_not_helpful">Not Helpful</span>
                            </button>
                        </div>
                        <div class="feedback-message" id="feedbackMessage" style="display:none;"></div>
                    </div>

                    <!-- Tips Section -->
                    <div id="tips-section" class="tips-section" style="display:none;">
                        <div class="tips-header">
                            <div class="tips-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="5"/>
                                    <line x1="12" x2="12" y1="1" y2="3"/>
                                    <line x1="12" x2="12" y1="21" y2="23"/>
                                    <line x1="4.22" x2="5.64" y1="4.22" y2="5.64"/>
                                    <line x1="18.36" x2="19.78" y1="18.36" y2="18.36"/>
                                    <line x1="1" x2="3" y1="12" y2="12"/>
                                    <line x1="21" x2="23" y1="12" y2="12"/>
                                    <line x1="4.22" x2="5.64" y1="19.78" y2="18.36"/>
                                    <line x1="18.36" x2="19.78" y1="5.64" y2="4.22"/>
                                </svg>
                            </div>
                            <div>
                                <h3 data-translate="personalized_tips">Personalized Tips</h3>
                                <p data-translate="tips_subtitle">Skincare recommendations based on your analysis</p>
                            </div>
                        </div>
                        <div id="tips-content" class="tips-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
