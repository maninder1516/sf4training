((0 + IF(
    (
        (SELECT IFNULL(SUM(gross),0) FROM izmo_client_target ic INNER JOIN izmo_client_sales_target_category_detail ics on ic.id=ics.target_id WHERE ic.year= YEAR('2022-01-01')    AND  IF(ics.month <=6, 1, 2) =  IF(MONTH('2022-01-01') <=6, 1, 2)     and ic.account_id = cust_id and ics.client_sales_category_id = 1)
        * Working_Days('2022-01-01',(CASE WHEN YEAR('2022-12-31') = YEAR('2022-06-30')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-06-30') <=6, 1, 2)    THEN '2022-12-31' ELSE IF('2022-12-31' < '2022-06-30', '2022-12-31', '2022-06-30') END),business_unit_id)
    ) / 
    Working_Days((CASE WHEN YEAR('2022-01-01') = YEAR('2022-01-01')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-06-30') <=6, 1, 2)    THEN '2022-01-01' ELSE '2022-01-01' END),(CASE WHEN YEAR('2022-12-31') = YEAR('2022-06-30')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-06-30') <=6, 1, 2)    THEN '2022-06-30' ELSE '2022-06-30' END),3)=0, 
    
    (SUM(CASE WHEN report_date BETWEEN '2021-01-01' and '2021-06-30' THEN gross_amount ELSE 0 END) / Working_Days('2021-01-01','2021-06-30',3)) * Working_Days('2022-01-01',(CASE WHEN YEAR('2022-12-31') = YEAR('2022-06-30')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-06-30') <=6, 1, 2)    THEN '2022-12-31' ELSE '2022-06-30' END),3),
    
     (
        (SELECT IFNULL(SUM(gross),0) FROM izmo_client_target ic INNER JOIN izmo_client_sales_target_category_detail ics on ic.id=ics.target_id WHERE ic.year= YEAR('2022-01-01')    AND  IF(ics.month <=6, 1, 2) =  IF(MONTH('2022-01-01') <=6, 1, 2)     and ic.account_id = cust_id and ics.client_sales_category_id = 1) 
        * Working_Days('2022-01-01',(CASE WHEN YEAR('2022-12-31') = YEAR('2022-06-30')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-06-30') <=6, 1, 2)    THEN '2022-12-31' ELSE IF('2022-12-31' < '2022-06-30', '2022-12-31', '2022-06-30') END),business_unit_id)
     ) / 
     Working_Days((CASE WHEN YEAR('2022-01-01') = YEAR('2022-01-01')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-06-30') <=6, 1, 2)    THEN '2022-01-01' ELSE '2022-01-01' END),(CASE WHEN YEAR('2022-12-31') = YEAR('2022-06-30')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-06-30') <=6, 1, 2)    THEN '2022-06-30' ELSE '2022-06-30' END),3)
     )
) 

+ 

IF(((SELECT IFNULL(SUM(gross),0) FROM izmo_client_target ic INNER JOIN izmo_client_sales_target_category_detail ics on ic.id=ics.target_id WHERE ic.year= YEAR('2022-07-01')    AND  IF(ics.month <=6, 1, 2) =  IF(MONTH('2022-07-01') <=6, 1, 2)     and ic.account_id = cust_id and ics.client_sales_category_id = 1) * Working_Days('2022-07-01',(CASE WHEN YEAR('2022-12-31') = YEAR('2022-12-31')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-12-31') <=6, 1, 2)    THEN '2022-12-31' ELSE IF('2022-12-31' < '2022-12-31', '2022-12-31', '2022-12-31') END),business_unit_id)) / Working_Days((CASE WHEN YEAR('2022-01-01') = YEAR('2022-07-01')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-12-31') <=6, 1, 2)    THEN '2022-07-01' ELSE '2022-07-01' END),(CASE WHEN YEAR('2022-12-31') = YEAR('2022-12-31')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-12-31') <=6, 1, 2)    THEN '2022-12-31' ELSE '2022-12-31' END),3)=0, 

(SUM(CASE WHEN report_date BETWEEN '2021-07-01' and '2021-12-31' THEN gross_amount ELSE 0 END) / Working_Days('2021-07-01','2021-12-31',3)) * Working_Days('2022-07-01',(CASE WHEN YEAR('2022-12-31') = YEAR('2022-12-31')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-12-31') <=6, 1, 2)    THEN '2022-12-31' ELSE '2022-12-31' END),3), 


((SELECT IFNULL(SUM(gross),0) FROM izmo_client_target ic INNER JOIN izmo_client_sales_target_category_detail ics on ic.id=ics.target_id WHERE ic.year= YEAR('2022-07-01')    AND  IF(ics.month <=6, 1, 2) =  IF(MONTH('2022-07-01') <=6, 1, 2)     and ic.account_id = cust_id and ics.client_sales_category_id = 1) * Working_Days('2022-07-01',(CASE WHEN YEAR('2022-12-31') = YEAR('2022-12-31')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-12-31') <=6, 1, 2)    THEN '2022-12-31' ELSE IF('2022-12-31' < '2022-12-31', '2022-12-31', '2022-12-31') END),business_unit_id)) / Working_Days((CASE WHEN YEAR('2022-01-01') = YEAR('2022-07-01')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-12-31') <=6, 1, 2)    THEN '2022-07-01' ELSE '2022-07-01' END),(CASE WHEN YEAR('2022-12-31') = YEAR('2022-12-31')   AND  IF(MONTH('2022-12-31') <=6, 1, 2) =  IF(MONTH('2022-12-31') <=6, 1, 2)    THEN '2022-12-31' ELSE '2022-12-31' END),3))) AS TargetRunningTotal2