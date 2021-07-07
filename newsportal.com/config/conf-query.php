<?php
  //Query to fetch all users whose username is supplied in :username;
  $query_login_fetch_by_username = "SELECT * FROM users WHERE username = :username";

  //Query to fetch all users whose user_id is supplied in :user_id;
  $query_login_fetch_by_userid = "SELECT * FROM users WHERE user_id = :id";

  //Query to fetch all users and display according to thier role ASCENDING and firstname ASCENDING;
  $query_fetch_users = "SELECT role, firstname, surname, user_email, username, user_id FROM users ORDER BY role ASC, firstname ASC";

  //Query to fetch all users who are ADMIN and display according tp their firstname ASCENDING;
  $query_fetch_users_admin = "SELECT role, firstname, surname, user_email, username, user_id FROM users WHERE role = 'ADMIN' ORDER BY firstname ASC";

  //Query to fetch all users who are USER and display according to their firstname ASCENDING;
  $query_fetch_users_users = "SELECT role, firstname, surname, user_email, username, user_id FROM users WHERE role = 'USER' ORDER BY firstname ASC";

  //Query to fetch all users whose role is VIEWER and display according to thier firstname ASCENDING;
  $query_fetch_users_viewers = "SELECT role, firstname, surname, user_email, username, user_id FROM users WHERE role = 'VIEWER' ORDER BY firstname ASC";

  //Query to fetch all users whose username is supplied in :username and role is either ADMIN or USER;
  $query_login_fetch_by_username_admin = "SELECT * FROM users WHERE username = :username AND role IN('ADMIN', 'USER')";

  //MySql syntax to insert a row into users table with the supplied data (User registration syntax);
  $query_join_insert = "INSERT INTO users(firstname, surname, user_email, username, password, role) VALUES( :firstname, :surname, :user_email, :username, :password, :role)";

  //MySql syntax to insert a row into messages table with the supplied data (Contact messages are inserted with this syntax);
  $query_contact_insert = "INSERT INTO messages (message_fname, message_sname, message_email, message_detail, message_date)
                          VALUES (:fname, :sname, :email, :detail, :send_date)";

  //Query to fetch categories from categories table and display according to their id ASCENDING;
  $query_categories_fetch = "SELECT * FROM categories ORDER BY category_id ASC";

  //Query to fetch categories from categories table whole title is as supplied and display according to their id ASCENDING;
  $query_categories_fetch_by_title = "SELECT * FROM categories WHERE category_title = :title ORDER BY category_id ASC";

  //Query to fetch all news along with category title and username associated with the news
  $query_article_fetch_all = "SELECT n.news_id, n.heading, n.detail, n.publish_date, c.category_id, c.category_title, u.user_id, u.username
                          FROM news n
                          JOIN categories c
                          ON n.category_id = c.category_id
                          JOIN users u
                          ON u.user_id = n.user_id
                          ORDER BY n.publish_date DESC, n.news_id DESC";

  //Query to fetch all the news, author name and category whose category id is as supplied;
  $query_article_fetch_cat_id = "SELECT n.news_id, n.heading, n.detail, n.publish_date, c.category_id, c.category_title, u.user_id, u.username
                            FROM news n
                            JOIN categories c
                            ON n.category_id = c.category_id
                            JOIN users u
                            ON u.user_id = n.user_id
                            AND c.category_id = :id
                            ORDER BY n.publish_date DESC, n.news_id DESC";

  //Query to fetch all the news, author name and category whose author id is as supplied;
  $query_article_fetch_session_id = "SELECT n.news_id, n.heading, n.detail, n.publish_date, c.category_id, c.category_title, u.user_id, u.username
                            FROM news n
                            JOIN categories c
                            ON n.category_id = c.category_id
                            JOIN users u
                            ON u.user_id = n.user_id
                            AND u.user_id = :id
                            ORDER BY n.publish_date DESC, n.news_id DESC";

  //Query to fetch all the news, author name and category whose news id is as supplied;
  $query_article_fetch_news_id = "SELECT n.news_id, n.heading, n.detail, n.publish_date, c.category_id, c.category_title, u.user_id, u.username
                            FROM news n
                            JOIN categories c
                            ON n.category_id = c.category_id
                            JOIN users u
                            ON u.user_id = n.user_id
                            AND n.news_id = :id
                            ORDER BY n.publish_date DESC, n.news_id DESC";

  //Query to fetch all the news, author name and category whose username matches the supplied pattern;
  $query_article_fetch_user = "SELECT n.news_id, n.heading, n.detail, n.publish_date, c.category_id, c.category_title, u.user_id, u.username
                          FROM news n
                          JOIN categories c
                          ON n.category_id = c.category_id
                          JOIN users u
                          ON u.user_id = n.user_id
                          AND u.username LIKE :data
                          ORDER BY n.publish_date DESC, n.news_id DESC";

  //Query to fetch all the news, author name and category whose category title matches the supplied pattern;
  $query_article_fetch_category = "SELECT n.news_id, n.heading, n.detail, n.publish_date, c.category_id, c.category_title, u.user_id, u.username
                          FROM news n
                          JOIN categories c
                          ON n.category_id = c.category_id
                          JOIN users u
                          ON u.user_id = n.user_id
                          AND c.category_title LIKE :data
                          ORDER BY n.publish_date DESC, n.news_id DESC";

  //Query to fetch all the news, author name and category whose detail matches the supplied pattern;
  $query_article_fetch_detail = "SELECT n.news_id, n.heading, n.detail, n.publish_date, c.category_id, c.category_title, u.user_id, u.username
                          FROM news n
                          JOIN categories c
                          ON n.category_id = c.category_id
                          JOIN users u
                          ON u.user_id = n.user_id
                          AND n.detail LIKE :data
                          ORDER BY n.publish_date DESC, n.news_id DESC";

  //Query to fetch all the news, author name and category whose news title matches the supplied pattern;
  $query_article_fetch_title = "SELECT n.news_id, n.heading, n.detail, n.publish_date, c.category_id, c.category_title, u.user_id, u.username
                          FROM news n
                          JOIN categories c
                          ON n.category_id = c.category_id
                          JOIN users u
                          ON u.user_id = n.user_id
                          AND n.heading LIKE :data
                          ORDER BY n.publish_date DESC, n.news_id DESC";

  //Query to fetch categories from categories table
  $query_fetch_category_id = "SELECT category_title, category_id FROM categories";

  //Query to fetch category title from categories table whose id is as supplied
  $query_fetch_category_name = "SELECT category_title FROM categories WHERE category_id = :id";

  //An array of queries used for search functionality
  $query_array_search = [
    'author' => $query_article_fetch_user,
    'category' => $query_article_fetch_category,
    'title' => $query_article_fetch_title,
    'detail' => $query_article_fetch_detail
  ];

  //Query to fetch everything from news table
  $query_fetch_news_id = "SELECT * FROM news";

  //Query to fetch comments, username from comments table whose parent id and news id are as supplied
  $query_fetch_comments = "SELECT c.comment_id, c.publish_date, c.publishable, c.detail, u.username, u.user_id
                          FROM comments c
                          JOIN users u
                          ON c.user_id = u.user_id
                          AND c.parent_comment_id = :p_id
                          AND c.news_id = :n_id
                          ORDER BY c.publish_date DESC";

  //Query to count the number of published comments associated with the news whose news id is as supplied
  $query_comment_count = "SELECT COUNT(c.comment_id) co
                        FROM comments c
                        WHERE c.news_id = :n_id
                        AND c.publishable = 'YES'
                        GROUP BY c.news_id";

  //MySql syntax to insert a record into comments table, with non-publishable tag by default
  $query_insert_comments = "INSERT INTO comments(detail, publish_date, publishable, parent_comment_id, user_id, news_id)
                          VALUES (:detail, :publish_date, 'NO', :parent, :user_id, :news_id)";

  //MySql syntax to insert a record into categories table
  $query_insert_category = "INSERT INTO categories(category_title) VALUES (:category_title)";

  //MySql syntax to update category title with supplied titel in categories table whose category id is as supplied
  $query_update_category = "UPDATE categories SET category_title = :category_title WHERE category_id = :category_id";

  //MySql syntax to delete a record from categories whose category id is as supplied
  $query_delete_category = "DELETE FROM categories WHERE category_id = :category_id";

  //MySql syntax to delete a record from users table whose user id is as supplied
  $query_delete_users = "DELETE FROM users WHERE user_id = :user_id";

  //MySql syntax to update role with supplied role of a user whose user id is as supplied
  $query_update_user = "UPDATE users SET role = :role WHERE user_id = :user_id";

  //Query to fetch news id and heading from news table whose associated user id is as supplied
  $query_article_fetch_userid = "SELECT n.news_id, n.heading
                          FROM news n
                          WHERE n.user_id = :id
                          ORDER BY n.publish_date DESC, n.news_id DESC";

  //MySql syntax to update publishable value by supplied value of a comment whose id is as supplied
  $query_comment_views = "UPDATE comments SET publishable = :publishable WHERE comment_id = :comment_id";

  //Query to fetch everything from messages table
  $query_fetch_messages = "SELECT * FROM messages ORDER BY message_date DESC";

  //MySql syntax to delete a record from messages table whose message id is as supplied
  $query_delete_messages = "DELETE FROM messages WHERE message_id = :message_id";

  //MySql syntax to delete a news whose id is as supplied
  $query_delete_article = "DELETE FROM news WHERE news_id = :news_id";

  //MySql syntax to insert a record into news table
  $query_add_article = "INSERT INTO news (heading, detail, publish_date, category_id, user_id)
                      VALUES (:heading, :detail, :publish_date, :category_id, :user_id)";

  //MySql syntax to update heading, detail and category from a news whose id is as supplied
  $query_edit_article = "UPDATE news SET heading = :heading, detail = :detail, category_id = :category_id WHERE news_id = :id";

  //Query to fetch username, comment detail, publish date and news title from comments whose user id is as supplied and is publishable
  $query_fetch_comments_show_up = "SELECT u.username, c.detail , c.publish_date, n.heading
                          FROM comments c
                          JOIN users u
                          ON c.user_id = u.user_id
                          JOIN news n
                          ON n.news_id = c.news_id
                          AND c.user_id = :u_id
                          AND c.publishable = 'YES'
                          ORDER BY c.publish_date DESC";
?>
