/*
 Navicat Premium Data Transfer

 Source Server         : SDI
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : localhost:3306
 Source Schema         : mvcframework

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 25/10/2021 19:47:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'user',
  `last_activity` datetime NULL DEFAULT NULL,
  `profile_img` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (20, 'owner_test', 'owner_test@gmail.com', '$2y$10$TflQNrcNiHH08jN7ibEZDO6HKV/Q8oG6AAksqNMTjmoNoZSfWAHrq', '2021-10-25 19:40:35', 'owner', '2021-10-25 16:40:51', NULL);
INSERT INTO `users` VALUES (21, 'admin_test', 'admin_test@gmail.com', '$2y$10$fy.1qoBFpOzT7Td6DwJ68ub8p4Ai3fjwpqxDQW8GXeIdxgEjUzGz2', '2021-10-25 19:44:39', 'admin', '2021-10-25 16:44:57', NULL);
INSERT INTO `users` VALUES (22, 'user_test', 'user_test@gmail.com', '$2y$10$LPMGpGkqFGzbCPY2mQagBuS/CSqfpHACqYp9OG55UQ4lBR.jtUyb6', '2021-10-25 19:46:38', 'user', '2021-10-25 16:46:51', NULL);

SET FOREIGN_KEY_CHECKS = 1;
