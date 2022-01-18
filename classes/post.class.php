<?php
class Post{
    private $db;
    private $id;
    private $content;
    private $date;
    private $userId;
    private $userFullName;
    private $profilePicUrl;
    private $filename;

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
                $this->filename = $result["filename"];
                $this->date = $result["postDate"];
                $this->userId = $result["userId"];
            }
        }
    }

    private function getUserData(){
        $sql = "SELECT firstName, lastName, profilePicUrl, gender FROM user WHERE id='".$this->userId."' LIMIT 1";
        if($result = $this->db->prepare($sql)){
            $result->execute();
            if($result->rowCount() == 1){
                $result = $result->fetchAll(PDO::FETCH_ASSOC);
                $result = $result[0];

                $this->userFullName = $result["firstName"] . " " . $result["lastName"];
                if(strlen($result["profilePicUrl"]) > 0){
                    $this->profilePicturePath = "uploads/" . $result["profilePicUrl"];
                } else {
                    $this->profilePicturePath = "assets/images/nopicture_".$result["gender"].".png";
                }
                
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

    private function formatDateForPost($date){
        // input 2021-01-31 00:00:00
        // output 03 januari 2021
        $day = substr($date, 8, 2);
        $month = substr($date, 5, 2);
        $year = substr($date, 0, 4);
        $month = $this->getMonthName($month);
        return intval($day) . " " . $month . " " . $year;
    }
    
    private function getMonthName($number){
        switch(intval($number)){
            case 1:
                return "januari";
            break;
            case 2:
                return "februari";
            break;
            case 3:
                return "maart";
            break;
            case 4:
                return "april";
            break;
            case 5:
                return "mei";
            break;
            case 6:
                return "juni";
            break;
            case 7:
                return "juli";
            break;
            case 8:
                return "augustus";
            break;
            case 9:
                return "september";
            break;
            case 10:
                return "oktober";
            break;
            case 11:
                return "november";
            break;
            case 12:
                return "december";
            break;
        }
    }

    public function render(){
        if($this->userExists()){
            echo "<div class=\"card post\">";
                echo "<div class=\"card-body\">";
                    echo "<h5 class=\"card-title\"><img src=\"".$this->profilePicturePath."\" alt=\"profielFoto\" class=\"profilePicture circle smallest\"><a class=\"link\" href=\"?profileId=".$this->userId."\">".$this->userFullName."</a><span class=\"text-muted\">".$this->formatDateForPost($this->date)."</span></h5>";
                    echo "<p class=\"card-text\">".$this->content."</p>";
                    if(strlen($this->filename) > 0){
                        echo "<img class=\"card-img-top\" src=\"uploads/".$this->filename."\" alt=\"Foto bij bericht\" style=\"width:250px;height:250px;display:block;object-fit:contain;\">";
                    }
                echo "</div>";
            echo "</div>";

        }
    }

}
?>