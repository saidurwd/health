SELECT PAT.sex, SUM(IF(PAT.age <= 5,1,0)) AS "AGE_GROUP_1", SUM(IF(PAT.age BETWEEN 5 AND 14,1,0)) AS "AGE_GROUP_2", SUM(IF(PAT.age BETWEEN 15 AND 24,1,0)) AS "AGE_GROUP_3", SUM(IF(PAT.age BETWEEN 25 AND 200,1,0)) AS "AGE_GROUP_4", COUNT(*) AS total 
FROM (
(SELECT pp.`patient`, pp.`created_on`, p.`age`, p.`sex` FROM os_patient_prescription pp LEFT OUTER JOIN os_patient p ON p.id=pp.patient) AS PAT
)
WHERE DATE_FORMAT(PAT.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("2019-01-01", "%Y-%m-%d") AND DATE_FORMAT(PAT.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("2019-03-31", "%Y-%m-%d") GROUP BY PAT.sex;


/*

SELECT DATE_SUB(CURDATE(), INTERVAL 10 MONTH) FROM DUAL 
SELECT DATE_SUB(CURDATE(), INTERVAL 10 YEAR) FROM DUAL 
SELECT  DATE_SUB(CURDATE(), INTERVAL `age` YEAR) FROM `os_patient` WHERE  birth_date='0000-00-00' AND age_type='Year'
UPDATE `os_patient` SET birth_date = DATE_SUB(CURDATE(), INTERVAL `age` YEAR) WHERE  birth_date='0000-00-00' AND age_type='Year'

SELECT * FROM `os_patient_prescription` 
WHERE `created_on`>='2019-02-01' AND `created_on`<'2019-02-28' -- 359
GROUP BY patient -- 325

SELECT * FROM `os_invoice_parent` 
WHERE `created_on`>='2019-02-01' AND `created_on`<'2019-02-28' -- 492
GROUP BY patient -- 397

SELECT `patient`,`created_on`,(SELECT P.`age` FROM os_patient P WHERE patient=P.`id`) AS age FROM os_patient_prescription AS PAT
WHERE DATE_FORMAT(PAT.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("2019-02-01", "%Y-%m-%d") AND DATE_FORMAT(PAT.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("2019-02-28", "%Y-%m-%d")

SELECT SUM(IF(PAT.age <= 5,1,0)) AS "AGE_GROUP_1", SUM(IF(PAT.age BETWEEN 6 AND 14,1,0)) AS "AGE_GROUP_2", SUM(IF(PAT.age BETWEEN 15 AND 24,1,0)) AS "AGE_GROUP_3", SUM(IF(PAT.age BETWEEN 25 AND 200,1,0)) AS "AGE_GROUP_4", COUNT(*) AS total 
FROM ((SELECT `patient`,`created_on`,(SELECT P.`age` FROM os_patient P WHERE patient=P.`id`) AS age FROM os_patient_prescription) AS PAT)
WHERE DATE_FORMAT(PAT.`created_on`, "%Y-%m-%d") >= DATE_FORMAT("2019-02-01", "%Y-%m-%d") AND DATE_FORMAT(PAT.`created_on`, "%Y-%m-%d") <= DATE_FORMAT("2019-02-28", "%Y-%m-%d")
*/