/*
Navicat SQLite Data Transfer

Source Server         : Rewind
Source Server Version : 30623
Source Host           : localhost:0

Target Server Type    : SQLite
Target Server Version : 30623
File Encoding         : 65001

Date: 2014-09-22 10:23:08
*/

PRAGMA foreign_keys = OFF;

-- ----------------------------
-- Table structure for "main"."page_meta"
-- ----------------------------
DROP TABLE "main"."page_meta";
CREATE TABLE "page_meta" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"uri"  TEXT,
"title"  TEXT,
"description"  TEXT,
"keywords"  TEXT
);