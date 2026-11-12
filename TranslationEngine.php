<?php

namespace CivicMind\Services;

class TranslationEngine {
    private static array $dictionary = [
        'en' => [
            'welcome' => 'Welcome to CivicMind X',
            'tagline' => 'AI Digital Life Operating System',
            'report_issue' => 'Report Civic Issue',
            'scam_detector' => 'Scam & Fraud Detector',
            'scheme_finder' => 'Scheme Discovery',
            'bill_intelligence' => 'Bill Intelligence',
            'find_providers' => 'Find Local Services',
            'heatmap' => 'Community Heatmap',
            'predictive_ai' => 'Predictive Intelligence',
            'ai_prompt_placeholder' => 'Describe your problem in plain language... (e.g. "Streetlight near my house is not working")',
            'analyze_btn' => 'Analyze with Civic AI',
            'submit_complaint' => 'Submit Official Complaint',
        ],
        'ta' => [
            'welcome' => 'சிவிக்மைண்ட் எக்ஸ்-க்கு நல்வரவு',
            'tagline' => 'செயற்கை நுண்ணறிவு டிஜிட்டல் வாழ்க்கை இயங்குதளம்',
            'report_issue' => 'குறைபாட்டைப் புகாரளிக்கவும்',
            'scam_detector' => 'மோசடி கண்டறிதல்',
            'scheme_finder' => 'அரசு திட்டங்கள்',
            'bill_intelligence' => 'கட்டண பகுப்பாய்வு',
            'find_providers' => 'உள்ளூர் சேவை வழங்குநர்கள்',
            'heatmap' => 'சமூக வெப்ப வரைபடம்',
            'predictive_ai' => 'முன்கணிப்பு நுண்ணறிவு',
            'ai_prompt_placeholder' => 'உங்கள் பிரச்சனையை எளிய தமிழில் விவரிக்கவும்... (எ.கா. "எங்கள் வீட்டின் முன் தெருவிளக்கு எரியவில்லை")',
            'analyze_btn' => 'AI மூலம் பகுப்பாய்வு செய்க',
            'submit_complaint' => 'அதிகாரப்பூர்வ புகார் அளிக்கவும்',
        ],
        'hi' => [
            'welcome' => 'सिविकमाइंड एक्स में आपका स्वागत है',
            'tagline' => 'एआई डिजिटल लाइफ ऑपरेटिंग सिस्टम',
            'report_issue' => 'नागरिक समस्या की शिकायत करें',
            'scam_detector' => 'स्कैम और धोखाधड़ी डिटेक्टर',
            'scheme_finder' => 'सरकारी योजना खोजें',
            'bill_intelligence' => 'बिल इंटेलिजेंस',
            'find_providers' => 'स्थानीय सेवा प्रदाता',
            'heatmap' => 'कम्युनिटी हीटमैप',
            'predictive_ai' => 'पूर्वानुमानित इंटेलिजेंस',
            'ai_prompt_placeholder' => 'अपनी समस्या को सरल भाषा में लिखें... (जैसे "मेरी गली की स्ट्रीटलाइट खराब है")',
            'analyze_btn' => 'एआई से विश्लेषण करें',
            'submit_complaint' => 'आधिकारिक शिकायत दर्ज करें',
        ],
    ];

    public static function translate(string $key, string $lang = 'en'): string {
        return self::$dictionary[$lang][$key] ?? self::$dictionary['en'][$key] ?? $key;
    }

    public static function getVoiceConfig(string $lang = 'en'): array {
        return match ($lang) {
            'ta' => ['code' => 'ta-IN', 'voice_name' => 'Google தமிழ்'],
            'hi' => ['code' => 'hi-IN', 'voice_name' => 'Google हिन्दी'],
            default => ['code' => 'en-US', 'voice_name' => 'Google US English'],
        };
    }
}
