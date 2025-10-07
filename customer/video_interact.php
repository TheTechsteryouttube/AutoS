<?php
session_start();
include("../includes/db_connect.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$video_id = intval($_POST['video_id']);
$action = $_POST['action'] ?? "";

if ($action === "like" || $action === "dislike") {
    $con->query("DELETE FROM video_interactions WHERE video_id=$video_id AND user_id=$user_id AND type IN ('like','dislike')");
    $stmt = $con->prepare("INSERT INTO video_interactions (video_id,user_id,type) VALUES (?,?,?)");
    $stmt->bind_param("iis",$video_id,$user_id,$action);
    $stmt->execute();
} elseif ($action === "comment") {
    $comment = trim($_POST['comment']);
    if ($comment !== "") {
        $stmt = $con->prepare("INSERT INTO video_interactions (video_id,user_id,type,comment) VALUES (?,?,?,?)");
        $type = "comment";
        $stmt->bind_param("iiss",$video_id,$user_id,$type,$comment);
        $stmt->execute();
        $username = $con->query("SELECT username FROM users WHERE user_id=$user_id")->fetch_assoc()['username'];
        echo json_encode(["success"=>true,"username"=>$username,"comment"=>$comment,"created_at"=>date("Y-m-d H:i:s")]);
        exit();
    }
}

// Updated counts
$likes = $con->query("SELECT COUNT(*) AS c FROM video_interactions WHERE video_id=$video_id AND type='like'")->fetch_assoc()['c'];
$dislikes = $con->query("SELECT COUNT(*) AS c FROM video_interactions WHERE video_id=$video_id AND type='dislike'")->fetch_assoc()['c'];

echo json_encode(["likes"=>$likes,"dislikes"=>$dislikes]);
