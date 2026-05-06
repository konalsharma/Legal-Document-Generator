<?php
header('Content-Type: application/json');

$openai_api_key = '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'; // KEEP THIS SAFE!

$data = json_decode(file_get_contents('php://input'), true);
$inputText = $data['text'] ?? '';

if (!$inputText) {
  http_response_code(400);
  echo json_encode(['error' => 'No text provided']);
  exit;
}

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: Bearer ' . $openai_api_key,
  'Content-Type: application/json'
]);

$postData = json_encode([
  'model' => 'gpt-4o',
  'messages' => [
    [
      'role' => 'system',
      'content' => 'You are an AI-powered legal assistant for Indian law. The following content is extracted from one or more uploaded images or PDF pages. Each section begins with the image or page identifier (e.g., "WhatsApp Image 2025-06-19 at ...jpeg") followed by the OCR text from that page. There may be repeated headers or file names; ignore obvious duplication or artifacts and fuse the content into a coherent understanding.

Your task is to analyze the entire multi-page document and provide the output in the structured format below. Do not repeat the same information multiple times if it\'s duplicated across pages—consolidate it.

Structured Analysis:
1. **Document Type:** Identify the type of document (e.g., Civil Suit, Lease Agreement, Summons, Service Contract, Notice).
2. **Parties Involved:** List the parties and their roles (Plaintiff/Defendant, Lessor/Lessee, Employer/Employee, etc.).
3. **Jurisdiction / Venue:** Which court, authority, or governing body is concerned.
4. **Effective Dates / Timeline:** Key dates (cause of action, execution date, filing date, deadlines, duration).
5. **Key Facts / Background:** Core factual narrative that the document conveys.
6. **Main Claims / Obligations / Terms:** For litigation: allegations and relief sought. For contracts/agreements: major terms, duties, payment, use, restrictions.
7. **Legal Provisions Referenced:** Any statutes, sections, acts (e.g., Indian Contract Act, Specific Relief Act, BNS sections, CPC, BNSS, IPC or CRPC for older cases than 2023 etc.) explicitly or implicitly invoked.
8. **Reliefs / Remedies / Outcomes Sought:** Injunctions, damages, specific performance, termination, etc.
9. **Risks / Red Flags:** Anything ambiguous, potentially harmful, missing (e.g., unclear parties, vague term, lack of termination clause).
10. **Plain-language Summary:** 4–5 sentence summary that a non-lawyer could understand.
11. **Next Steps / Suggested Actions:** Practical things the recipient should do (e.g., file reply, obtain evidence, serve notice, negotiate, seek injunction).

Additional Instructions:
- Consolidate content across pages; if the same clause or name appears multiple times because of repeated headers, mention it once.
- If any part is unclear or appears truncated, note that and specify which page it came from.
- Keep the tone professional and concise.
- End with: “This is general legal information, not a substitute for professional legal advice.”'
    ],
    [
      'role' => 'user',
      'content' => $inputText
    ]
  ],
  'temperature' => 0.5
]);



curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
  echo $response;
} else {
  http_response_code($httpCode);
  echo json_encode(['error' => 'OpenAI API error', 'details' => $response]);
}
?>
