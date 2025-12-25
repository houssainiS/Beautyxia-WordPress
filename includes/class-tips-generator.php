<?php
/**
 * Tips Generator Class
 * Generates personalized beauty tips based on analysis results
 * Synchronized with tips.js and includes all original content
 */

class Tips_Generator {
    
    private static $skin_type_tips = array(
        'Dry' => array(
            'en' => array(
                'Use a gentle, cream-based cleanser to avoid stripping natural oils.',
                'Apply a rich, hydrating moisturizer twice daily.',
                'Look for products with hyaluronic acid and ceramides.',
                'Use a humidifier in your bedroom to add moisture to the air.',
                'Avoid hot water when washing your face - use lukewarm instead.',
                'Consider using a facial oil as the last step in your nighttime routine.',
            ),
            'fr' => array(
                'Utilisez un nettoyant doux à base de crème pour éviter d\'éliminer les huiles naturelles.',
                'Appliquez une crème hydratante riche deux fois par jour.',
                'Recherchez des produits contenant de l\'acide hyaluronique et des céramides.',
                'Utilisez un humidificateur dans votre chambre pour ajouter de l\'humidité à l\'air.',
                'Évitez l\'eau chaude pour vous laver le visage - utilisez de l\'eau tiède.',
                'Considérez l\'utilisation d\'une huile faciale comme dernière étape de votre routine nocturne.',
            ),
            'ar' => array(
                'استخدم منظف لطيف كريمي لتجنب إزالة الزيوت الطبيعية.',
                'ضع مرطب غني ومرطب مرتين يومياً.',
                'ابحث عن منتجات تحتوي على حمض الهيالورونيك والسيراميد.',
                'استخدم جهاز ترطيب في غرفة نومك لإضافة الرطوبة للهواء.',
                'تجنب الماء الساخن عند غسل وجهك - استخدم الماء الفاتر بدلاً من ذلك.',
                'فكر في استخدام زيت الوجه كخطوة أخيرة في روتينك الليلي.',
            ),
        ),
        'Normal' => array(
            'en' => array(
                'Maintain your routine with a balanced cleanser and moisturizer.',
                'Use sunscreen daily to prevent premature aging.',
                'Incorporate antioxidants like vitamin C into your morning routine.',
                'Exfoliate 1-2 times per week to maintain smooth skin texture.',
                'Stay hydrated by drinking plenty of water throughout the day.',
                'Consider adding a weekly hydrating mask to your routine.',
            ),
            'fr' => array(
                'Maintenez votre routine avec un nettoyant et une crème hydratante équilibrés.',
                'Utilisez un écran solaire quotidiennement pour prévenir le vieillissement prématuré.',
                'Incorporez des antioxydants comme la vitamine C dans votre routine matinale.',
                'Exfoliez 1 à 2 fois par semaine pour maintenir une texture de peau lisse.',
                'Restez hydraté en buvant beaucoup d\'eau tout au long de la journée.',
                'Considérez l\'ajout d\'un masque hydratant hebdomadaire à votre routine.',
            ),
            'ar' => array(
                'حافظ على روتينك بمنظف ومرطب متوازن.',
                'استخدم واقي الشمس يومياً لمنع الشيخوخة المبكرة.',
                'أدرج مضادات الأكسدة مثل فيتامين سي في روتينك الصباحي.',
                'قشر البشرة 1-2 مرات في الأسبوع للحفاظ على ملمس البشرة الناعم.',
                'ابق رطباً بشرب الكثير من الماء طوال اليوم.',
                'فكر في إضافة قناع مرطب أسبوعي لروتينك.',
            ),
        ),
        'Oily' => array(
            'en' => array(
                'Use a foaming or gel-based cleanser to control excess oil.',
                'Look for non-comedogenic, oil-free moisturizers.',
                'Incorporate salicylic acid or niacinamide to regulate oil production.',
                'Use clay masks 1-2 times per week to absorb excess oil.',
                'Don\'t skip moisturizer - dehydrated skin can produce more oil.',
                'Consider using blotting papers throughout the day instead of over-washing.',
            ),
            'fr' => array(
                'Utilisez un nettoyant moussant ou à base de gel pour contrôler l\'excès de sébum.',
                'Recherchez des crèmes hydratantes non comédogènes et sans huile.',
                'Incorporez de l\'acide salicylique ou de la niacinamide pour réguler la production de sébum.',
                'Utilisez des masques d\'argile 1 à 2 fois par semaine pour absorber l\'excès de sébum.',
                'Ne sautez pas la crème hydratante - une peau déshydratée peut produire plus de sébum.',
                'Considérez l\'utilisation de papiers buvards tout au long de la journée au lieu de trop laver.',
            ),
            'ar' => array(
                'استخدم منظف رغوي أو جل للتحكم في الزيت الزائد.',
                'ابحث عن مرطبات غير كوميدوجينية وخالية من الزيت.',
                'أدرج حمض الساليسيليك أو النياسيناميد لتنظيم إنتاج الزيت.',
                'استخدم أقنعة الطين 1-2 مرات في الأسبوع لامتصاص الزيت الزائد.',
                'لا تتخط المرطب - البشرة المجففة يمكن أن تنتج المزيد من الزيت.',
                'فكر في استخدام أوراق التنشيف طوال اليوم بدلاً من الغسل المفرط.',
            ),
        ),
    );

    private static $eye_color_tips = array(
        'Brown' => array(
            'en' => array(
                "Enhance brown eyes with warm eyeshadow tones like gold, bronze, and copper.",
                "Purple and plum shades create beautiful contrast with brown eyes.",
                "Try navy blue eyeliner instead of black for a softer look.",
                "Green eyeshadows can make brown eyes appear more vibrant.",
            ),
            'fr' => array(
                "Rehaussez les yeux bruns avec des tons d'ombre à paupières chauds comme l'or, le bronze et le cuivre.",
                "Les nuances violettes et prune créent un beau contraste avec les yeux bruns.",
                "Essayez l'eye-liner bleu marine au lieu du noir pour un look plus doux.",
                "Les ombres à paupières vertes peuvent faire paraître les yeux bruns plus vibrants.",
            ),
            'ar' => array(
                "عزز العيون البنية بألوان ظلال العيون الدافئة مثل الذهبي والبرونزي والنحاسي.",
                "الألوان البنفسجية والخوخية تخلق تباين جميل مع العيون البنية.",
                "جرب كحل العيون الأزرق الداكن بدلاً من الأسود للحصول على مظهر أنعم.",
                "ظلال العيون الخضراء يمكن أن تجعل العيون البنية تبدو أكثر حيوية.",
            ),
        ),
        'Blue' => array(
            'en' => array(
                "Warm tones like peach, coral, and bronze complement blue eyes beautifully.",
                "Orange and copper eyeshadows make blue eyes pop.",
                "Brown eyeliner can be more flattering than black for everyday wear.",
                "Avoid blue eyeshadows that match your eye color exactly.",
            ),
            'fr' => array(
                "Les tons chauds comme la pêche, le corail et le bronze complètent magnifiquement les yeux bleus.",
                "Les ombres à paupières orange et cuivre font ressortir les yeux bleus.",
                "L'eye-liner brun peut être plus flatteur que le noir pour un usage quotidien.",
                "Évitez les ombres à paupières bleues qui correspondent exactement à votre couleur d'yeux.",
            ),
            'ar' => array(
                "الألوان الدافئة مثل الخوخي والمرجاني والبرونزي تكمل العيون الزرقاء بشكل جميل.",
                "ظلال العيون البرتقالية والنحاسية تجعل العيون الزرقاء تبرز.",
                "كحل العيون البني يمكن أن يكون أكثر إطراءً من الأسود للاستخدام اليومي.",
                "تجنب ظلال العيون الزرقاء التي تطابق لون عينيك تماماً.",
            ),
        ),
        'Green' => array(
            'en' => array(
                "Purple and plum shades are perfect for making green eyes stand out.",
                "Red and pink tones create stunning contrast with green eyes.",
                "Golden and bronze shades enhance the warmth in green eyes.",
                "Brown eyeliner often looks more natural than black with green eyes.",
            ),
            'fr' => array(
                "Les nuances violettes et prune sont parfaites pour faire ressortir les yeux verts.",
                "Les tons rouges et roses créent un contraste saisissant avec les yeux verts.",
                "Les nuances dorées et bronze rehaussent la chaleur des yeux verts.",
                "L'eye-liner brun paraît souvent plus naturel que le noir avec les yeux verts.",
            ),
            'ar' => array(
                "الألوان البنفسجية والخوخية مثالية لإبراز العيون الخضراء.",
                "الألوان الحمراء والوردية تخلق تباين مذهل مع العيون الخضراء.",
                "الألوان الذهبية والبرونزية تعزز الدفء في العيون الخضراء.",
                "كحل العيون البني غالباً ما يبدو أكثر طبيعية من الأسود مع العيون الخضراء.",
            ),
        ),
        'Hazel' => array(
            'en' => array(
                "Bring out golden flecks with warm browns and golds.",
                "Purple shades can emphasize green tones in hazel eyes.",
                "Experiment with both warm and cool tones to see what works best.",
                "Bronze and copper eyeshadows enhance the complexity of hazel eyes.",
            ),
            'fr' => array(
                "Faites ressortir les paillettes dorées avec des bruns et des ors chauds.",
                "Les nuances violettes peuvent accentuer les tons verts des yeux noisette.",
                "Expérimentez avec des tons chauds et froids pour voir ce qui fonctionne le mieux.",
                "Les ombres à paupières bronze et cuivre rehaussent la complexité des yeux noisette.",
            ),
            'ar' => array(
                "أبرز البقع الذهبية بالألوان البنية والذهبية الدافئة.",
                "الألوان البنفسجية يمكن أن تؤكد على الألوان الخضراء في العيون العسلية.",
                "جرب الألوان الدافئة والباردة لترى ما يناسبك أكثر.",
                "ظلال العيون البرونزية والنحاسية تعزز تعقيد العيون العسلية.",
            ),
        ),
        'Gray' => array(
            'en' => array(
                "Silver and charcoal eyeshadows complement gray eyes naturally.",
                "Purple and plum shades can make gray eyes appear more blue.",
                "Warm browns can bring out any golden flecks in gray eyes.",
                "Black eyeliner creates striking definition with gray eyes.",
            ),
            'fr' => array(
                "Les ombres à paupières argentées et anthracite complètent naturellement les yeux gris.",
                "Les nuances violettes et prune peuvent faire paraître les yeux gris plus bleus.",
                "Les bruns chauds peuvent faire ressortir les paillettes dorées des yeux gris.",
                "L'eye-liner noir crée une définition frappante avec les yeux gris.",
            ),
            'ar' => array(
                "ظلال العيون الفضية والرمادية الداكنة تكمل العيون الرمادية بشكل طبيعي.",
                "الألوان البنفسجية والخوخية يمكن أن تجعل العيون الرمادية تبدو أكثر زرقة.",
                "الألوان البنية الدافئة يمكن أن تبرز أي بقع ذهبية في العيون الرمادية.",
                "كحل العيون الأسود يخلق تعريف مذهل مع العيون الرمادية.",
            ),
        ),
    );

    private static $acne_severity_tips = array(
        0 => array(
            'en' => array(
                'Maintain your current skincare routine to keep skin clear.',
                'Use a gentle cleanser and non-comedogenic moisturizer.',
                'Don\'t forget daily sunscreen to prevent post-inflammatory hyperpigmentation.',
                'Consider incorporating antioxidants like vitamin C for overall skin health.',
            ),
            'fr' => array(
                'Maintenez votre routine de soins actuelle pour garder une peau claire.',
                'Utilisez un nettoyant doux et une crème hydratante non comédogène.',
                'N\'oubliez pas l\'écran solaire quotidien pour prévenir l\'hyperpigmentation post-inflammatoire.',
                'Considérez l\'incorporation d\'antioxydants comme la vitamine C pour la santé globale de la peau.',
            ),
            'ar' => array(
                'حافظ على روتين العناية بالبشرة الحالي للحفاظ على بشرة صافية.',
                'استخدم منظف لطيف ومرطب غير كوميدوجيني.',
                'لا تنس واقي الشمس اليومي لمنع فرط التصبغ بعد الالتهاب.',
                'فكر في دمج مضادات الأكسدة مثل فيتامين سي لصحة البشرة العامة.',
            ),
        ),
        1 => array(
            'en' => array(
                'Use a gentle salicylic acid cleanser to prevent clogged pores.',
                'Spot treat blemishes with benzoyl peroxide or tea tree oil.',
                'Avoid over-cleansing, which can irritate skin and worsen breakouts.',
                'Use non-comedogenic products to prevent further clogging.',
            ),
            'fr' => array(
                'Utilisez un nettoyant doux à l\'acide salicylique pour prévenir les pores obstrués.',
                'Traitez localement les imperfections avec du peroxyde de benzoyle ou de l\'huile d\'arbre à thé.',
                'Évitez le nettoyage excessif, qui peut irriter la peau et aggraver les éruptions.',
                'Utilisez des produits non comédogènes pour éviter d\'autres obstructions.',
            ),
            'ar' => array(
                'استخدم منظف لطيف بحمض الساليسيليك لمنع انسداد المسام.',
                'عالج البقع موضعياً ببيروكسيد البنزويل أو زيت شجرة الشاي.',
                'تجنب التنظيف المفرط، الذي يمكن أن يهيج البشرة ويزيد من البثور.',
                'استخدم منتجات غير كوميدوجينية لمنع المزيد من الانسداد.',
            ),
        ),
        2 => array(
            'en' => array(
                'Consider adding a retinoid to your nighttime routine (start slowly).',
                'Use salicylic acid or benzoyl peroxide products consistently.',
                'Don\'t pick at blemishes - this can lead to scarring.',
                'Consider seeing a dermatologist for personalized treatment options.',
            ),
            'fr' => array(
                'Considérez l\'ajout d\'un rétinoïde à votre routine nocturne (commencez lentement).',
                'Utilisez des produits à l\'acide salicylique ou au peroxyde de benzoyle de manière cohérente.',
                'Ne touchez pas aux imperfections - cela peut conduire à des cicatrices.',
                'Considérez consulter un dermatologue pour des options de traitement personnalisées.',
            ),
            'ar' => array(
                'فكر في إضافة ريتينويد لروتينك الليلي (ابدأ ببطء).',
                'استخدم منتجات حمض الساليسيليك أو بيروكسيد البنزويل بانتظام.',
                'لا تلمس البثور - هذا يمكن أن يؤدي إلى ندبات.',
                'فكر في رؤية طبيب الجلدية لخيارات العلاج الشخصية.',
            ),
        ),
        3 => array(
            'en' => array(
                'Consult with a dermatologist for prescription treatment options.',
                'Be gentle with your skin - avoid harsh scrubbing or over-treatment.',
                'Consider professional treatments like chemical peels or light therapy.',
                'Maintain a consistent, gentle routine while seeking professional help.',
            ),
            'fr' => array(
                'Consultez un dermatologue pour des options de traitement sur ordonnance.',
                'Soyez doux avec votre peau - évitez le gommage dur ou le sur-traitement.',
                'Considérez des traitements professionnels comme les peelings chimiques ou la thérapie par la lumière.',
                'Maintenez une routine cohérente et douce tout en cherchant une aide professionnelle.',
            ),
            'ar' => array(
                'استشر طبيب الجلدية لخيارات العلاج بوصفة طبية.',
                'كن لطيفاً مع بشرتك - تجنب الفرك القاسي أو الإفراط في العلاج.',
                'فكر في العلاجات المهنية مثل التقشير الكيميائي أو العلاج بالضوء.',
                'حافظ على روتين ثابت ولطيف أثناء طلب المساعدة المهنية.',
            ),
        ),
    );
    
    /**
     * Generate tips based on analysis data
     */
    public static function generate_tips($analysis_data, $language = 'en') {
        $tips = array();
        
        // 1. Add skin type tips
        if (!empty($analysis_data['skin_type'])) {
            $skin_type = $analysis_data['skin_type'];
            if (isset(self::$skin_type_tips[$skin_type][$language])) {
                $tips = array_merge($tips, array_slice(self::$skin_type_tips[$skin_type][$language], 0, 2));
            }
        }
        
        // 2. Add eye color tips (New logic synchronized with tips.js)
        if (!empty($analysis_data['left_eye_color'])) {
            $left_eye = $analysis_data['left_eye_color'];
            if (isset(self::$eye_color_tips[$left_eye][$language])) {
                $tips = array_merge($tips, array_slice(self::$eye_color_tips[$left_eye][$language], 0, 1));
            }
        }
        
        if (!empty($analysis_data['right_eye_color'])) {
            $right_eye = $analysis_data['right_eye_color'];
            // Only add if different from left eye color to avoid redundant tips
            if ($right_eye !== ($analysis_data['left_eye_color'] ?? '') && isset(self::$eye_color_tips[$right_eye][$language])) {
                $tips = array_merge($tips, array_slice(self::$eye_color_tips[$right_eye][$language], 0, 1));
            }
        }
        
        // 3. Add acne severity tips
        if (isset($analysis_data['acne_pred'])) {
            $acne_level = intval($analysis_data['acne_pred']);
            if (isset(self::$acne_severity_tips[$acne_level][$language])) {
                $tips = array_merge($tips, array_slice(self::$acne_severity_tips[$acne_level][$language], 0, 2));
            }
        }
        
        // Remove duplicates and limit to 8 tips
        $tips = array_unique($tips);
        return array_slice($tips, 0, 8);
    }
}