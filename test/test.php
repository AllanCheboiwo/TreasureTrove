<?php
    // create algorithm for social media timeline
    // a post can be [post, comment, quote, retweet]
    // a post is a post on a social media site
    // a comment is a post with a parent post
    // a quote is a post with a quoted post
    // a repost is a post that references a post
    // when user posts, add to timeline
    // when user comments, add to timeline
    // when user quotes, add to timeline
    // when user reposts, add to timeline
    // when user likes, add to timeline
    // when user follows another user, add their posts to timeline
    // when user unfollows another user, remove their posts from timeline
    // when user blocks another user, remove their posts from timeline
    // when user unblocks another user, add their if they re follow them
    // when user deletes a post, remove from timeline
    // when user deletes a comment, remove from timeline
    // when user deletes a quote, remove from timeline
    // when user deletes a repost, remove from timeline
    // when user deletes a like, remove from timeline
    // sort timeline reverse chronologically and by rank or relevance, whichever is higher for each post (rank is determined by likes, comments, reposts, follows etc)
    // when a user views another user's post, suggest their post to the user if they are not following them already (if they are following them, they will see their posts in their timeline)
    // suggestions should not be frequent, but should be relevant to the user (based on their interests, likes, comments, reposts, follows, etc)

class Timeline {
    public static function AddPost($post) {
        // add post to timeline

    }
    public static function AddComment($comment) {
        // add comment to timeline
    }
    public static function AddQuote($quote) {
        // add quote to timeline
    }
    public static function AddRepost($repost) {
        // add repost to timeline
    }
    public static function AddLike($like) {
        // add like to timeline
    }
    public static function AddFollow($follow) {
        // add follow to timeline
    }
    public static function AddUnfollow($unfollow) {
        // add unfollow to timeline
    }
    public static function AddBlock($block) {
        // add block to timeline
    }
    public static function AddUnblock($unblock) {
        // add unblock to timeline
    }
    public static function DeletePost($post) {
        // delete post from timeline
    }
    public static function DeleteComment($comment) {
        // delete comment from timeline
    }
    public static function DeleteQuote($quote) {
        // delete quote from timeline
    }
    public static function DeleteRepost($repost) {
        // delete repost from timeline
    }
    public static function DeleteLike($like) {
        // delete like from timeline
    }
    public static function GetTimeline($userId) {
        // get timeline for user
    }
    public static function GetTimelineForUser($userId) {
        // get timeline for user
    }
    public static function GetTimelineForPost($postId) {
        // get timeline for post
    }
    public static function GetTimelineForComment($commentId) {
        // get timeline for comment
    }
    public static function GetTimelineForQuote($quoteId) {
        // get timeline for quote
    }
    public static function GetTimelineForRepost($repostId) {
        // get timeline for repost
    }
    public static function GetTimelineForLike($likeId) {
        // get timeline for like
    }
    public static function GetTimelineForFollow($followId) {
        // get timeline for follow
    }
    public static function GetTimelineForUnfollow($unfollowId) {
        // get timeline for unfollow
    }
    public static function GetTimelineForBlock($blockId) {
        // get timeline for block
    }
    public static function GetTimelineForUnblock($unblockId) {
        // get timeline for unblock
    }
}

// start timeline algorithm

$sql = "SELECT * FROM posts WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $post = new Post($row["id"], $row["userId"], $row["content"], $row["dateCreated"]);
        Timeline::AddPost($post);
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM comments WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $comment = new Comment($row["id"], $row["userId"], $row["postId"], $row["content"], $row["dateCreated"]);
        Timeline::AddComment($comment);
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM quotes WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $quote = new Quote($row["id"], $row["userId"], $row["postId"], $row["content"], $row["dateCreated"]);
        Timeline::AddQuote($quote);
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM reposts WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $repost = new Repost($row["id"], $row["userId"], $row["postId"], $row["dateCreated"]);
        Timeline::AddRepost($repost);
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM likes WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $like = new Like($row["id"], $row["userId"], $row["postId"], $row["dateCreated"]);
        Timeline::AddLike($like);
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM follows WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $follow = new Follow($row["id"], $row["userId"], $row["followedUserId"], $row["dateCreated"]);
        Timeline::AddFollow($follow);
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM unfollows WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $unfollow = new Unfollow($row["id"], $row["userId"], $row["unfollowedUserId"], $row["dateCreated"]);
        Timeline::AddUnfollow($unfollow);
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM blocks WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $block = new Block($row["id"], $row["userId"], $row["blockedUserId"], $row["dateCreated"]);
        Timeline::AddBlock($block);
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM unblocks WHERE userId = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $unblock = new Unblock($row["id"], $row["userId"], $row["unblockedUserId"], $row["dateCreated"]);
        Timeline::AddUnblock($unblock);
    }
} else {
    echo "0 results";
}

// build timeline algorithm from the results above
// newest post first
// newest comment first
// newest quote first
// newest repost first
// newest like first

usort($posts, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

usort($comments, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

usort($quotes, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

usort($reposts, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

usort($likes, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

usort($follows, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

usort($unfollows, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

usort($blocks, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

usort($unblocks, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

// build timeline
$timeline = array();

foreach ($posts as $post) {
    $timeline[] = $post;
}

foreach ($comments as $comment) {
    $timeline[] = $comment;
}

foreach ($quotes as $quote) {
    $timeline[] = $quote;
}

foreach ($reposts as $repost) {
    $timeline[] = $repost;
}

foreach ($likes as $like) {
    $timeline[] = $like;
}

foreach ($follows as $follow) {
    $timeline[] = $follow;
}

foreach ($unfollows as $unfollow) {
    $timeline[] = $unfollow;
}

foreach ($blocks as $block) {
    $timeline[] = $block;
}

foreach ($unblocks as $unblock) {
    $timeline[] = $unblock;
}

// sort timeline by dateCreated
usort($timeline, function($a, $b) {
    return $a->dateCreated < $b->dateCreated;
});

// output timeline
foreach ($timeline as $item) {
echo $item->dateCreated . " " . $item->id . " " . $item->userId . " " . $item->postId . " " . $item->content . " " . $item->dateCreated . " " . $item->followedUserId . " " . $item->unfollowedUserId . " " . $item->blockedUserId . " " . $item->unblockedUserId . " ";
}

$conn->close();

// end timeline algorithm