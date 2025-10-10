Looking at my care-plan.php code above create a checkin-geolocation.php page
1. When user click on start button <a href="#" id="startShiftBtn" class="btn btn-primary">
    <i class="bi bi-play-circle"></i> Start
</a> in care-plan.php
2. Write a js code to copy client data from one object store to another
3. Get the client userId from the url tab and copy the necessary fields in the tbl_schedule_calls below into tbl_general_client_form according to the way it's being arranged below
4. It should copy row in tbl_schedule_calls into tbl_daily_shift_records where userId.tbl_schedule_calls is equal to userId.tbl_daily_shift_records

-- Use `uryyToeSS4` VARCHAR(255) in tbl_schedule_calls to Select the client_poster_code, client_latitude, client_longitude from tbl_general_client_form where uryyToeSS4 is equal to uryyToeSS4 in tbl_general_client_form.
-- Get the miles between the client and the carer using the Haversine formula or any other method.
-- Use `first_carer_Id` VARCHAR(255) in tbl_schedule_calls to get the carer's col_pay_rate, col_rate_type and col_mileage from tbl_general_team_form e.g where first_carer_Id.tbl_schedule_calls = uryyTteamoeSS4.tbl_general_team_form.
CREATE TABLE `tbl_schedule_calls` (
`userId` VARCHAR(255), -- User ID
`client_name` VARCHAR(255), -- Client Name
`col_area_Id` VARCHAR(255), -- Client area ID
`uryyToeSS4` VARCHAR(255), -- unique ID
`client_area` VARCHAR(255), -- Client Area
`first_carer` VARCHAR(255), -- Carer Name
`first_carer_Id` VARCHAR(255), -- Carer Special ID
`care_calls` VARCHAR(255), -- Care Calls (e.g. morning, lunch, tea, bed, extra morning, extra lunch, extra tea, extra bed)
`dateTime_in` VARCHAR(255), -- Time In
`dateTime_out` VARCHAR(255), -- Time Out
`col_run_name` VARCHAR(255), -- Run Name
`col_required_carers` VARCHAR(255), -- Number of Carers Required (e.g. 1 or 2)
`Clientshift_Date` VARCHAR(255), -- Shift Date (e.g. visits for the day)
`call_status` VARCHAR(255), -- Call Status (e.g. Scheduled, Not completed, or Completed)
`col_company_Id` VARCHAR(255), -- Company ID(this is not an integer it's a string)
PRIMARY KEY (`userId`)
);

-- This columns will update when the user checks in
CREATE TABLE `tbl_daily_shift_records` (
`userId` VARCHAR(255), -- User ID
`shift_status` VARCHAR(255), -- Checked in(it's a static string)
`shift_date` VARCHAR(255), -- Shift Date (e.g. visits for the day)
`planned_timeIn` VARCHAR(255), -- Time In
`planned_timeOut` VARCHAR(255), -- Time Out
`shift_start_time` VARCHAR(255), -- Get the current time when the user checks in
`client_name` VARCHAR(255), -- Client Name
`uryyToeSS4` VARCHAR(255), -- unique ID
`col_care_call` VARCHAR(255), -- Care Calls (e.g. morning, lunch, tea, bed, extra morning, extra lunch, extra tea, extra bed)
`client_group` VARCHAR(255), -- Client Area
`carer_Name` VARCHAR(255), -- Carer Name
`col_carer_Id` VARCHAR(255), -- Carer Special ID
`col_area_Id` VARCHAR(255), -- Client area ID
`col_company_Id` VARCHAR(255), -- Company ID(this is not an integer it's a string)
`col_call_status` VARCHAR(255), -- Call Status (e.g. Scheduled, Not completed, or Completed)
`col_miles` VARCHAR(255), -- Miles(user the client postal code and carer's current distance(HTML5 navigator.geolocation) to calculate the distance)
`col_mileage` VARCHAR(255), -- Mileage (e.g. 0.45 per mile)
`col_visit_status` VARCHAR(255), -- set it to True (It's a static string)
`col_visit_confirmation` VARCHAR(255), -- set it to Unconfirmed (It's a static string)
`col_care_call_Id` VARCHAR(255),-- User ID(tbl_schedule_calls)
`col_postcode` VARCHAR(255), -- client postal code
`dateTime` VARCHAR(255), -- creation or update time
PRIMARY KEY (`userId`)
);