<?php
/**
 * Tips Generator Class
 * Generates personalized beauty tips based on analysis results
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
        
        // Add skin type tips
        if (!empty($analysis_data['skin_type'])) {
            $skin_type = $analysis_data['skin_type'];
            if (isset(self::$skin_type_tips[$skin_type][$language])) {
                $tips = array_merge($tips, array_slice(self::$skin_type_tips[$skin_type][$language], 0, 2));
            }
        }
        
        // Add acne severity tips
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
