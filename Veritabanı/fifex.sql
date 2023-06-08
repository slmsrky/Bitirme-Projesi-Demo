CREATE TABLE `blocked_table`(
  `blocked_id` int(11) NOT NULL,
  `blocking_user` varchar(50) NOT NULL,
  `blocked_user` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `call_request` (
  `id` int(11) NOT NULL,
  `call_user` varchar(50) NOT NULL,
  `called_user` varchar(50) NOT NULL,
  `call_value` int(11) NOT NULL,
  `room_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `code_table` (
  `code_id` int(11) NOT NULL,
  `user_nick` varchar(50) NOT NULL,
  `code_1` int(11) NOT NULL,
  `code_2` int(11) NOT NULL,
  `code_3` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `friendship_table` (
  `friendship_id` int(11) NOT NULL,
  `user_1` varchar(50) NOT NULL,
  `user_2` varchar(50) NOT NULL,
  `friend_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `group_message_table` (
  `g_message_id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `message_text` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `group_table` (
  `group_id` int(11) NOT NULL,
  `created_user_nick` varchar(50) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `group_biography` varchar(100) NOT NULL DEFAULT 'Merhaba, Biz FIFEX Kullanıcısıyız.',
  `group_photo_path` varchar(100) NOT NULL DEFAULT 'images\\Icon.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `group_users_table` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `user_message_table` (
  `u_message_id` int(11) NOT NULL,
  `user_1` varchar(50) NOT NULL,
  `user_2` varchar(50) NOT NULL,
  `message_text` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `user_table` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_surname` varchar(50) NOT NULL,
  `user_nick` varchar(50) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_biography` varchar(100) NOT NULL DEFAULT 'Merhaba, Ben FIFEX Kullanıcısıyım.',
  `user_photo_path` varchar(100) NOT NULL DEFAULT 'images\\Icon.png',
  `code_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `blocked_table`
  ADD PRIMARY KEY (`blocked_id`);

ALTER TABLE `call_request`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `code_table`
  ADD PRIMARY KEY (`code_id`);

ALTER TABLE `friendship_table`
  ADD PRIMARY KEY (`friendship_id`);

ALTER TABLE `group_message_table`
  ADD PRIMARY KEY (`g_message_id`);

ALTER TABLE `group_table`
  ADD PRIMARY KEY (`group_id`);

ALTER TABLE `group_users_table`
  ADD PRIMARY KEY (`group_id`);

ALTER TABLE `user_message_table`
  ADD PRIMARY KEY (`u_message_id`);

ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `blocked_table`
  MODIFY `blocked_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `call_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `code_table`
  MODIFY `code_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `friendship_table`
  MODIFY `friendship_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `group_message_table`
  MODIFY `g_message_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `group_table`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `group_users_table`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_message_table`
  MODIFY `u_message_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;