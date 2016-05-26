/* ========================== CMSGears Payment ========================================== */

--
-- Table structure for table `cmg_payment`
--

DROP TABLE IF EXISTS `cmg_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` bigint(20) DEFAULT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,   
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `amount` float(8,2) NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `data` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_payment_1` (`createdBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Constraints for table `cmg_payment`
--
ALTER TABLE `cmg_payment`
	ADD CONSTRAINT `fk_cmg_payment_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`);