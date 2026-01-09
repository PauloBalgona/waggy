<?php
// Simple API endpoint to get likes count for a post (for polling)
use Illuminate\Support\Facades\DB;

require_once __DIR__ . '/../../vendor/autoload.php';

if (!isset($_GET['post_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing post_id']);
    exit;
}

$postId = (int) $_GET['post_id'];

$count = DB::table('post_likes')->where('post_id', $postId)->count();
echo json_encode(['likes_count' => $count]);
