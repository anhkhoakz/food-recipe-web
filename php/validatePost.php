<?php
session_start();

// Function to check if any word is offensive
function validateWords($words)
{
    $blocklist_path_1 = __DIR__ . '/../data/offensiveWords/en_offensive_words.json';
    $blocklist_path_2 = __DIR__ . '/../data/offensiveWords/vn_offensive_words.conf';
    $word_block_list_1 = getBlocklistContent($blocklist_path_1);
    $word_block_list_2 = getBlocklistContent($blocklist_path_2);

    return in_array($words, $word_block_list_1) || in_array($words, $word_block_list_2);
}

function getBlocklistContent($blocklist_path)
{
    return file($blocklist_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

// Function to check all paragraphs for offensive words
function checkParagraphsForOffensiveWords($text)
{
    // Split the text into paragraphs
    $paragraphs = preg_split('/\n+/', $text);

    // Loop through each paragraph
    foreach ($paragraphs as $paragraph) {
        // Trim whitespace from the paragraph
        $cleanParagraph = trim($paragraph);

        // Check if the paragraph contains offensive words
        if (validateWords($cleanParagraph)) {
            // Offensive words found in the paragraph
            return true;
        }
    }

    // No offensive words found in any paragraph
    return false;
}

// Function to validate the recipe form
function validateRecipeForm($title, $description, $videoLink, $imageSize)
{
    // Validate recipe title
    if (strlen($title) < 1 || strlen($title) > 50) {
        return "Title must be between 1 and 50 words.";
    }

    // Validate recipe description
    $wordCount = str_word_count($description);
    if ($wordCount < 10 || $wordCount > 1000) {
        return "Description must be between 10 and 1000 words.";
    }

    // Check for offensive words in title
    if (checkParagraphsForOffensiveWords($title)) {
        return "Title contains offensive words.";
    }

    // Check for offensive words in description
    if (checkParagraphsForOffensiveWords($description)) {
        return "Description contains offensive words.";
    }

    // Validate video link format
    // if (!filter_var($videoLink, FILTER_VALIDATE_URL)) {
    //     return "Video link must be in the format of a valid HTTPS link.";
    // }

    // Validate uploaded image file size
    // if (!filter_var($imageSize, FILTER_VALIDATE_URL)) {
    //     return "Image link must be in the format of a valid HTTPS link.";
    // }

    // All validation passed
    return null;
}
