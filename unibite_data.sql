

INSERT INTO `user` (`username`, `email`, `pass`, `name`, `reg_date`) VALUES
('teststudent', 'up1234567@upnet.gr', '$2y$10$FXuvhCDuIdGSI0TAlxZrcuaxYTs1V1/lsACaAQJ8EeqRphO.98o5O', 'Test Student', '2026-08-17 12:27:36'),
('up1234589@gmail.com', 'up1234589@gmail.com', '$2y$10$gS2kMSLE1bYcA57Be3UQLOE8byXzAoaZnxJ.UvlpaEd7SmGsskdKW', 'Alex hat', '2026-08-19 16:00:09');



INSERT INTO `student` (`username`, `email`, `credits`, `street`, `number`, `city`, `postcode`, `mobile`) VALUES
('teststudent', 'up1234567@upnet.gr', 5, 'Kanakari', 60, 'patra', 26211, '+306982082900'),
('up1234589@gmail.com', 'up1234589@gmail.com', 5, 'korinthou', 10, 'patras', 26221, '6900001234');



INSERT INTO `cook` (`username`, `email`, `street`, `number`, `city`, `postcode`, `mobile`) VALUES
('teststudent', 'up1234567@upnet.gr', 'Kanakari', 60, 'patra', 26211, '+306982082900'),
('up1234589@gmail.com', 'up1234589@gmail.com', 'korinthou', 10, 'patras', 26221, '6900001234');



INSERT INTO `dish` (`id`, `cook`, `title`, `description`, `allergens`, `photos_url`, `pickup_location`, `pickup_time`, `latitude`, `longitude`, `portions`, `credits_per_portion`, `reg_date`) VALUES
(17, 'teststudent', 'Μακαρόνια με κιμά', 'Φρέσκα μακαρόνια με κιμά', 'Κρέας', 'uploads/makaronia me kima.jpeg', 'κανακαρι 60', '2026-08-20 15:23:00', 38.2499984, 21.7365360, 4, 1, '2026-08-17 15:23:45'),
(18, 'teststudent', 'Πίτσα', 'Φρέσκια πίτσα', 'Τυρί', 'uploads/pizza.jpeg', 'κανακαρι 80', '2026-08-25 15:26:00', 38.2475260, 21.7367080, 3, 1, '2026-08-17 15:26:20'),
(19, 'teststudent', 'Λαζάνια', 'Φρέσκα λαζάνια', 'γαλα, κιμας', 'uploads/lazania .jpeg', 'κανακαρι 60', '2026-08-26 16:08:00', 38.2464475, 21.7340469, 4, 1, '2026-08-17 16:08:28'),
(20, 'teststudent', 'μακαρονια με κιμα', 'φρεσκα μακαρονια', 'γαλα, κιμας', 'uploads/makaronia.png', 'κανακαρι 60', '2026-08-27 18:13:00', 38.2378188, 21.7319870, 4, 1, '2026-08-17 18:13:19'),
(21, 'teststudent', 'Cinnamon Rolls', 'Freshly made Cinnamon rolls', 'cream', 'uploads/cinnamon_rolls.jpeg', 'κανακαρι 60', '2026-08-21 18:27:00', 38.2456386, 21.7361931, 7, 1, '2026-08-17 18:27:28'),
(22, 'up1234589@gmail.com', 'παστιτσαδα', 'φρέσκο φαγητό', 'κρέας', 'uploads/images_pastitsada.png', 'κανακαρι 40', '2026-08-27 16:03:00', 38.2491222, 21.7409134, 5, 1, '2026-08-19 16:03:57');



INSERT INTO `request` (`id`, `stu_username`, `cook_username`, `dish_id`, `portions`, `credit_cost`, `status`, `pickup_status`, `rating`, `request_datetime`, `reply_datetime`, `pickup_datetime`, `rated_datetime`) VALUES
(3, 'teststudent', 'teststudent', 21, 1, 1, 'pending', NULL, NULL, '2026-08-17 18:33:56', NULL, NULL, NULL),
(4, 'up1234589@gmail.com', 'teststudent', 19, 1, 1, 'pending', NULL, NULL, '2026-08-19 16:06:06', NULL, NULL, NULL),
(5, 'up1234589@gmail.com', 'teststudent', 17, 1, 1, 'pending', NULL, NULL, '2026-08-19 23:02:58', NULL, NULL, NULL);