-- phpMyAdmin SQL Dump
-- version 3.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2009 at 11:59 AM
-- Server version: 5.0.76
-- PHP Version: 5.2.9-pl2-gentoo

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: ``
--

-- --------------------------------------------------------

--
-- Table structure for table `debugs`
--

CREATE TABLE `debugs` (
  `id` bigint(20) NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `desc` varchar(2048) default NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `thing_id` BIGINT(20) default 0,
  `thing_type` varchar(45) default NULL,
  `name` varchar(45) default NULL,
  `type` varchar(45) default NULL,
  `parent_id` bigint(20) unsigned default NULL,
  `parameters` varchar(512) default NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) NOT NULL auto_increment,
  `user_id` bigint(20) default NULL,
  `session_cookie` varchar(255) default NULL,
  `level` varchar(128) NOT NULL,
  `ip` varchar(20) default NULL,
  `name` varchar(128) NOT NULL,
  `p1` varchar(255) default NULL,
  `p2` varchar(255) default NULL,
  `p3` varchar(255) default NULL,
  `p4` varchar(255) default NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL auto_increment,
  `login` varchar(30) NOT NULL UNIQUE,
  `hashed_password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `phone` varchar(45) default NULL,
  `profile_picture` varchar(255) default NULL,
  `status` varchar(45) default NULL,
  `blocked` INTEGER(1) NOT NULL DEFAULT 0,
  `last_login_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` bigint(20) NOT NULL auto_increment,
  `hash` varchar(40) NOT NULL UNIQUE,
  `thing_id` BIGINT(20) default 0,
  `thing_type` varchar(45) default NULL,
  `action` varchar(255) NOT NULL,
  `used` bigint(20) NOT NULL default 0,
  `auto_delete` INTEGER(1) DEFAULT 1,
  `status` tinyint(1) NOT NULL,
  `created_at` DATETIME DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- CONSTRAINTS
--

-- logs
ALTER TABLE `logs` ADD CONSTRAINT logs_user_id_users_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- --------------------------------------------------------
