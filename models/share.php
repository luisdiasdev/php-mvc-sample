<?php
class ShareModel extends Model {
    public function index() {
        $this->query('SELECT * FROM shares ORDER BY create_date DESC');
        $rows = $this->resultSet();
        return $rows;
    }

    public function add() {
        // Sanitize POST
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if($post['submit']) {
            // Check input
            if($post['title'] == '' || $post['body'] == '' || $post['link'] == '') {
                Messages::setMessage('Please fill in all fields.', true);
                return;
            }            

            // Insert
            $this->query('INSERT INTO shares (title, body, link, user_id) VALUES(:title, :body, :link, :user_id)');
            $this->bind(':title', $post['title']);
            $this->bind(':body', $post['body']);
            $this->bind(':link', $post['link']);
            $this->bind(':user_id', $_SESSION['user_data']['id']);
            $this->execute();
            // Verify
            if($this->lastInsertId()) {
                // Redirect
                header('Location: ' . ROOT_URL . 'shares');
            }
        }
    }
}