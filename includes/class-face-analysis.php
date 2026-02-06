<?php
/**
 * Main Face Analysis Plugin Class
 */

class Face_Analysis_Plugin {
    
    // 1. UPDATE: Changed endpoint to the new WordPress-specific view
    private $api_endpoint = 'https://beautyai.duckdns.org/wordpress/analyze/';
    private $feedback_endpoint = 'https://beautyai.duckdns.org/submit-feedback/';
    
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_shortcode('face_analysis', array($this, 'render_shortcode'));
        add_action('wp_footer', array($this, 'add_inline_scripts'));
    }
    
    /**
     * Enqueue CSS and JavaScript assets
     */
    public function enqueue_assets() {
        // Only enqueue on pages with the shortcode
        if (!is_admin() && $this->has_shortcode('face_analysis')) {
            // Enqueue CSS
            wp_enqueue_style(
                'face-analysis-style',
                FACE_ANALYSIS_PLUGIN_URL . 'assets/css/style.css',
                array(),
                FACE_ANALYSIS_PLUGIN_VERSION
            );
            
            wp_enqueue_script(
                'face-analysis-translations',
                FACE_ANALYSIS_PLUGIN_URL . 'assets/js/translations.js',
                array(),
                FACE_ANALYSIS_PLUGIN_VERSION,
                true
            );
            
            // Enqueue Tips Generator JS (depends on translations)
            wp_enqueue_script(
                'face-analysis-tips',
                FACE_ANALYSIS_PLUGIN_URL . 'assets/js/tips.js',
                array('face-analysis-translations'),
                FACE_ANALYSIS_PLUGIN_VERSION,
                true
            );
            
            // Enqueue Main JS (depends on tips)
            wp_enqueue_script(
                'face-analysis-main',
                FACE_ANALYSIS_PLUGIN_URL . 'assets/js/main.js',
                array('face-analysis-tips'),
                FACE_ANALYSIS_PLUGIN_VERSION,
                true
            );
            
            // 2. UPDATE: Retrieve the API Key saved during the handshake
            // CHANGED: Use 'fa_api_key' to match admin-settings.php
            $stored_api_key = get_option('fa_api_key', '');

            // Localize script with API endpoints, nonce, AND credentials
            wp_localize_script('face-analysis-main', 'faceAnalysisConfig', array(
                'apiEndpoint' => $this->api_endpoint,
                'feedbackEndpoint' => $this->feedback_endpoint,
                'nonce' => wp_create_nonce('face_analysis_nonce'),
                'siteUrl' => site_url(),
                
                // --- NEW FIELDS FOR DJANGO AUTH ---
                'apiKey' => $stored_api_key,
                'shopUrl' => get_site_url(), // Sends the WP site URL as the identifier
            ));
        }
    }
    
    /**
     * Check if page has the shortcode
     */
    private function has_shortcode($tag) {
        global $post;
        if (!isset($post)) {
            return false;
        }
        return has_shortcode($post->post_content, $tag);
    }
    
    /**
     * Render the face analysis shortcode
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => 'Face Analysis',
            'description' => 'Upload an image to analyze facial features and receive personalized beauty tips.',
        ), $atts, 'face_analysis');
        
        ob_start();
        include FACE_ANALYSIS_PLUGIN_DIR . 'templates/face-analysis-template.php';
        return ob_get_clean();
    }
    
    /**
     * Add inline scripts for translations and initialization
     */
    public function add_inline_scripts() {
        if (!$this->has_shortcode('face_analysis')) {
            return;
        }
        ?>
        <script>
            // Translations object
            const translations = {
                en: {
                    title: "Face Analysis",
                    description: "Upload an image to analyze facial features and receive personalized beauty tips.",
                    lang_en: "EN",
                    lang_fr: "FR",
                    lang_ar: "AR",
                    upload_placeholder: "Upload or capture an image",
                    upload_button: "Upload Image",
                    use_camera: "Use Camera",
                    capture_photo: "Capture Photo",
                    stop_camera: "Stop Camera",
                    consent_text: "I agree to the processing of my photo for temporary analysis to receive beauty tips. I understand that my photo will not be stored.",
                    privacy_policy_text: "Read our",
                    privacy_policy_link: "Privacy Policy",
                    privacy_policy_details: "for more details.",
                    analyze_face: "Analyze Face",
                    upload_note: "PNG, JPG, or JPEG up to 10MB",
                    analyzing_title: "Analyzing Your Face",
                    analyzing_subtitle: "Please wait while we process your image...",
                    analysis_complete: "Analysis Complete",
                    analysis_results_subtitle: "Here are your personalized skin analysis results",
                    face_detected: "Face Detected Successfully",
                    analysis_confidence: "Analysis completed with high confidence",
                    skin_type: "Skin Type",
                    eye_colors: "Eye Colors",
                    left_eye: "Left:",
                    right_eye: "Right:",
                    acne_analysis: "Acne Analysis",
                    level: "Level:",
                    detected_issues: "Detected Issues",
                    personalized_tips: "Personalized Tips",
                    tips_subtitle: "Skincare recommendations based on your analysis",
                    detailed_analysis: "Detailed Analysis",
                    visual_breakdown: "Visual breakdown of detected areas and skin conditions",
                    issue_detection: "Issue Detection",
                    areas_highlighted: "Areas of concern highlighted",
                    skin_segmentation: "Skin Segmentation",
                    skin_areas_mapped: "Different skin areas mapped",
                    no_issues_detected: "No skin issues detected",
                    feedback_header: "How was your experience?",
                    feedback_helpful: "Helpful",
                    feedback_not_helpful: "Not Helpful",
                    feedback_thank_you: "Thank you for your feedback!",
                    feedback_submitted: "Your feedback has been submitted successfully.",
                    feedback_error: "Failed to submit feedback. Please try again."
                },
                fr: {
                    title: "Analyse Faciale",
                    description: "Téléchargez une image pour analyser les traits du visage et recevoir des conseils beauté personnalisés.",
                    lang_en: "EN",
                    lang_fr: "FR",
                    lang_ar: "AR",
                    upload_placeholder: "Télécharger ou capturer une image",
                    upload_button: "Télécharger Image",
                    use_camera: "Utiliser Caméra",
                    capture_photo: "Capturer Photo",
                    stop_camera: "Arrêter Caméra",
                    consent_text: "J'accepte le traitement de ma photo pour une analyse temporaire afin de recevoir des conseils beauté. Je comprends que ma photo ne sera pas stockée.",
                    privacy_policy_text: "Lisez notre",
                    privacy_policy_link: "Politique de Confidentialité",
                    privacy_policy_details: "pour plus de détails.",
                    analyze_face: "Analyser Visage",
                    upload_note: "PNG, JPG, ou JPEG jusqu'à 10MB",
                    analyzing_title: "Analyse de Votre Visage",
                    analyzing_subtitle: "Veuillez patienter pendant que nous traitons votre image...",
                    analysis_complete: "Analyse Terminée",
                    analysis_results_subtitle: "Voici vos résultats d'analyse de peau personnalisés",
                    face_detected: "Visage Détecté avec Succès",
                    analysis_confidence: "Analyse terminée avec une grande confiance",
                    skin_type: "Type de Peau",
                    eye_colors: "Couleurs des Yeux",
                    left_eye: "Gauche:",
                    right_eye: "Droite:",
                    acne_analysis: "Analyse d'Acné",
                    level: "Niveau:",
                    detected_issues: "Problèmes Détectés",
                    personalized_tips: "Conseils Personnalisés",
                    tips_subtitle: "Recommandations de soins de la peau basées sur votre analyse",
                    detailed_analysis: "Analyse Détaillée",
                    visual_breakdown: "Répartition visuelle des zones détectées et des conditions de peau",
                    issue_detection: "Détection de Problèmes",
                    areas_highlighted: "Zones préoccupantes mises en évidence",
                    skin_segmentation: "Segmentation de la Peau",
                    skin_areas_mapped: "Différentes zones de peau cartographiées",
                    no_issues_detected: "Aucun problème de peau détecté",
                    feedback_header: "Comment était votre expérience?",
                    feedback_helpful: "Utile",
                    feedback_not_helpful: "Pas Utile",
                    feedback_thank_you: "Merci pour votre retour!",
                    feedback_submitted: "Votre commentaire a été soumis avec succès.",
                    feedback_error: "Échec de l'envoi des commentaires. Veuillez réessayer."
                },
                ar: {
                    title: "تحليل الوجه",
                    description: "قم بتحميل صورة لتحليل ملامح الوجه والحصول على نصائح جمال شخصية.",
                    lang_en: "EN",
                    lang_fr: "FR",
                    lang_ar: "AR",
                    upload_placeholder: "تحميل أو التقاط صورة",
                    upload_button: "تحميل صورة",
                    use_camera: "استخدام الكاميرا",
                    capture_photo: "التقاط صورة",
                    stop_camera: "إيقاف الكاميرا",
                    consent_text: "أوافق على معالجة صورتي للتحليل المؤقت لتلقي نصائح الجمال. أفهم أن صورتي لن يتم تخزينها.",
                    privacy_policy_text: "اقرأ",
                    privacy_policy_link: "سياسة الخصوصية",
                    privacy_policy_details: "لمزيد من التفاصيل.",
                    analyze_face: "تحليل الوجه",
                    upload_note: "PNG أو JPG أو JPEG حتى 10 ميجابايت",
                    analyzing_title: "تحليل وجهك",
                    analyzing_subtitle: "يرجى الانتظار بينما نعالج صورتك...",
                    analysis_complete: "اكتمل التحليل",
                    analysis_results_subtitle: "إليك نتائج تحليل بشرتك الشخصية",
                    face_detected: "تم اكتشاف الوجه بنجاح",
                    analysis_confidence: "اكتمل التحليل بثقة عالية",
                    skin_type: "نوع البشرة",
                    eye_colors: "ألوان العيون",
                    left_eye: "اليسار:",
                    right_eye: "اليمين:",
                    acne_analysis: "تحليل حب الشباب",
                    level: "المستوى:",
                    detected_issues: "المشاكل المكتشفة",
                    personalized_tips: "نصائح شخصية",
                    tips_subtitle: "توصيات العناية بالبشرة بناءً على تحليلك",
                    detailed_analysis: "تحليل مفصل",
                    visual_breakdown: "تفصيل بصري للمناطق المكتشفة وحالات البشرة",
                    issue_detection: "اكتشاف المشاكل",
                    areas_highlighted: "المناطق المثيرة للقلق مميزة",
                    skin_segmentation: "تقسيم البشرة",
                    skin_areas_mapped: "مناطق البشرة المختلفة مرسومة",
                    no_issues_detected: "لم يتم اكتشاف مشاكل في البشرة",
                    feedback_header: "كيف كانت تجربتك؟",
                    feedback_helpful: "مفيد",
                    feedback_not_helpful: "غير مفيد",
                    feedback_thank_you: "شكراً لك على ملاحظاتك!",
                    feedback_submitted: "تم إرسال ملاحظاتك بنجاح.",
                    feedback_error: "فشل إرسال الملاحظات. يرجى المحاولة مرة أخرى."
                }
            };
        </script>
        <?php
    }
}