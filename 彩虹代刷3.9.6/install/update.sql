ALTER TABLE `shua_tools`
ADD COLUMN `zid` int(11) NOT NULL DEFAULT '1',
ADD COLUMN `input` varchar(20) DEFAULT NULL,
ADD COLUMN `inputs` varchar(80) DEFAULT NULL,
ADD COLUMN `alert` text DEFAULT NULL,
ADD COLUMN `is_curl` tinyint(2) NOT NULL DEFAULT '0',
ADD COLUMN `curl` varchar(100) DEFAULT NULL,
ADD COLUMN `repeat` tinyint(2) NOT NULL DEFAULT '0';

ALTER TABLE `shua_orders`
MODIFY COLUMN `zid` int(11) NOT NULL DEFAULT '1',
CHANGE COLUMN `qq` `input` varchar(64) NOT NULL,
ADD COLUMN `input2` varchar(64) DEFAULT NULL,
ADD COLUMN `input3` varchar(64) DEFAULT NULL,
ADD COLUMN `input4` varchar(64) DEFAULT NULL,
ADD COLUMN `input5` varchar(64) DEFAULT NULL;

ALTER TABLE `shua_kms`
ADD COLUMN `zid` int(11) NOT NULL DEFAULT '1';

ALTER TABLE `shua_pay`
CHANGE COLUMN `qq` `input` text NOT NULL;