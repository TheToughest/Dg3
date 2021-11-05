<?php
class Post{
    private $db;
    private $id;
    private $content;
    private $date;
    private $userId;
    private $userFullName;
    private $profilePicUrl;

    public function __construct($db, $id){
        $this->db = $db;
        $this->id = $id;
        $this->getPostData();
        $this->getUserData();
    }

    private function getPostData(){
        $sql = "SELECT * FROM post WHERE id=".$this->id;
        if($result = $this->db->prepare($sql)){
            $result->execute();
            if($result->rowCount() > 0){
                $result = $result->fetchAll(PDO::FETCH_ASSOC);
        
                $result = $result[0];
                $this->content = $result["content"];
                $this->date = $result["postDate"];
                $this->userId = $result["userId"];
            }
        }
    }

    private function getUserData(){
        $sql = "SELECT firstName, lastName, profilePicUrl FROM user WHERE id='".$this->userId."' LIMIT 1";
        if($result = $this->db->prepare($sql)){
            $result->execute();
            if($result->rowCount() == 1){
                $result = $result->fetchAll(PDO::FETCH_ASSOC);
                $result = $result[0];

                $this->userFullName = $result["firstName"] . " " . $result["lastName"];
                $this->profilePicturePath = $result["profilePicUrl"];
            }
        }
    }

    private function UserExists(){
        if(isset($this->userFullName) && strlen($this->userFullName) > 1){
            return true;
        } else {
            return false;
        }
    }

    public function render(){
        if($this->userExists()){
            echo "<div class=\"post\">";
                echo "<a href=\"?profileId=".$this->userId."\"><strong>".$this->userFullName."</strong></a>";
                echo "<span class=\"date\">".$this->date."</span>";
                echo "<p>".$this->content."</p>";
            echo "</div>";
        }
    }

}
?>