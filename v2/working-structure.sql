CREATE TABLE `tbl_schedule_calls` (
  `userId` VARCHAR(255), /*id*/
  `client_name` VARCHAR(255), /*name*/
  `client_area` VARCHAR(255), /*service*/
  `first_carer` VARCHAR(255), /*carers*/
  `first_carer_Id` VARCHAR(255), /*Carer id*/
  `care_calls` VARCHAR(255), /*For care calls e.g morning, lunch, tea, bed, extra morning, extra lunch, extra tea, extra bed*/
  `dateTime_in` VARCHAR(255), /*time_in*/
  `dateTime_out` VARCHAR(255), /*time_out*/
  `col_run_name` VARCHAR(255), /*Run name*/
  `col_required_carers` VARCHAR(255), /*numbers of carer*/
  `Clientshift_Date` VARCHAR(255), /*date*/
  `call_status` VARCHAR(255), /**/
  PRIMARY KEY (`userId`)
);