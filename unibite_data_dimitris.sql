

INSERT INTO `user` (`username`, `email`, `pass`, `name`, `reg_date`) VALUES
('Dimitris', 'papakia@gmail.com', '$2y$10$i37Npfp9.JXYoLB.unoUeOC1wqldCuOHZQHXF3oK52LDGX1MtlD9q', 'DIMITRIS BRIONIS', '2026-09-07 12:51:40'),
('Mitaras', 'ap@unibite.gr', '$2y$10$u092N5su9CR4CNxgBBda7e0x0BAJLb.T.xrzSL29NmDIqrfXv4oo.', 'Alex Parginos', '2026-08-10 14:24:30'),
('testakos1', 'dimvri112@gmail.com', '$2y$10$ybuzbRIA4x7C71EXJa0G1.VQdFwELUkoTyi7Tn16tef9cmhaBvvzK', 'testakos1 testakou', '2026-09-04 11:30:10'),
('testakos2', 'divri112@gmail.com', '$2y$10$4i/jz1Wz/UkEX.yddHA01eF4reRsq5PoKpN9obPh100PhHOioqvJW', 'testakos2 testakou', '2026-09-04 11:42:17'),
('testakos3', 'asdasd@gmail.com', '$2y$10$sRz3n.MDb/E3lbx69./KiO5SaY/fCHkAUG9UsNDbC/LoUWyEpL5E2', 'testakos2 testakou', '2026-09-04 11:46:12');


INSERT INTO `student` (`username`, `email`, `credits`, `street`, `number`, `city`, `postcode`, `mobile`) VALUES
('testakos1', 'dimvri112@gmail.com', 9, 'PATMOY', 7, 'PATRA', 49100, '+306945056779'),
('testakos2', 'divri112@gmail.com', 17, 'PATMOY', 7, 'PATRA', 49100, '+306945056779'),
('testakos3', 'asdasd@gmail.com', 1, 'PATMOY', 7, 'PATRA', 49100, '+306945056779');




INSERT INTO `cook` (`username`, `email`, `street`, `number`, `city`, `postcode`, `mobile`, `total_credits_earned`) VALUES
('testakos1', 'dimvri112@gmail.com', 'PATMOY', 7, 'PATRA', 49100, '+306945056779', 32),
('testakos2', 'divri112@gmail.com', 'PATMOY', 7, 'PATRA', 49100, '+306945056779', 28);

INSERT INTO `admin` (`username`, `email`) VALUES
('Mitaras', 'ap@unibite.gr'),
('Dimitris', 'papakia@gmail.com');

-- --------------------------------------------------------
--

INSERT INTO `dish` (`id`, `cook`, `title`, `description`, `allergens`, `photos_url`, `pickup_location`, `pickup_time`, `latitude`, `longitude`, `portions`, `credits_per_portion`, `reg_date`) VALUES
(1, 'testakos1', 'μακαρονια με κιμα του testakou1', 'einai simiwseis gia makaronia', 'exei kai allergiogona', 'uploads/makaronia.png', 'sadas', '2026-09-17 11:41:00', 38.2509668, 21.7408784, 0, 1, '2026-09-04 11:41:38'),
(2, 'testakos1', 'testakos 1', 'ASD', 'ASD', 'uploads/makaronia.png', 'DS', '2026-09-16 15:33:00', 38.2355340, 21.7277205, 0, 1, '2026-09-04 15:33:21'),
(3, 'testakos2', 'SD', 'SAD', 'ASD', NULL, 'ASD', '2026-08-14 15:34:00', 38.2164961, 21.7374500, 1, 1, '2026-09-04 15:34:12');

-- --------------------------------------------------------

INSERT INTO `request` (`id`, `stu_username`, `cook_username`, `dish_id`, `portions`, `credit_cost`, `status`, `pickup_status`, `rating`, `request_datetime`, `reply_datetime`, `pickup_datetime`, `rated_datetime`) VALUES
(1, 'testakos3', 'testakos1', 1, 4, 4, 'accepted', 'picked_up', NULL, '2026-09-04 11:49:08', '2026-09-04 11:51:32', '2026-09-04 11:51:35', NULL),
(2, 'testakos2', 'testakos1', 1, 5, 5, 'accepted', 'picked_up', '5', '2026-09-04 11:53:37', '2026-09-04 11:54:07', '2026-09-04 11:54:08', '2026-09-04 11:54:37'),
(3, 'testakos1', 'testakos2', 3, 4, 4, 'accepted', 'picked_up', '5', '2026-09-04 15:34:41', '2026-09-04 15:34:50', '2026-09-04 15:34:52', '2026-09-04 15:35:14'),
(4, 'testakos2', 'testakos1', 2, 3, 3, 'accepted', 'picked_up', '5', '2026-09-04 15:35:42', '2026-09-04 15:35:54', '2026-09-04 15:35:55', '2026-09-04 20:15:08'),
(5, 'testakos1', 'testakos2', 3, 3, 3, 'accepted', 'picked_up', '5', '2026-09-04 19:56:57', '2026-09-04 20:04:36', '2026-09-04 20:04:38', '2026-09-04 20:15:23'),
(6, 'testakos2', 'testakos1', 2, 2, 2, 'accepted', 'picked_up', '5', '2026-09-04 20:09:28', '2026-09-04 20:09:48', '2026-09-04 20:09:50', '2026-09-04 20:15:07'),
(7, 'testakos1', 'testakos2', 3, 2, 2, 'accepted', 'picked_up', '5', '2026-09-04 20:11:08', '2026-09-04 20:11:28', '2026-09-04 20:11:28', '2026-09-04 20:15:24'),
(8, 'testakos2', 'testakos1', 1, 5, 5, 'accepted', 'picked_up', '5', '2026-09-04 20:15:00', '2026-09-04 20:15:36', '2026-09-04 20:15:37', '2026-09-04 20:18:10'),
(9, 'testakos1', 'testakos2', 3, 4, 4, 'accepted', 'picked_up', '5', '2026-09-04 20:17:13', '2026-09-04 20:17:47', '2026-09-04 20:17:48', '2026-09-04 20:18:19'),
(10, 'testakos2', 'testakos1', 1, 1, 1, 'accepted', 'picked_up', NULL, '2026-09-04 20:18:06', '2026-09-04 20:18:32', '2026-09-04 20:18:33', NULL),
(11, 'testakos1', 'testakos2', 3, 3, 3, 'accepted', 'picked_up', NULL, '2026-09-06 10:34:29', '2026-09-06 10:34:50', '2026-09-06 10:35:26', NULL);

