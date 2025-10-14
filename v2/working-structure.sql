CREATE TABLE `tbl_general_client_form` (
    `userId` VARCHAR(255), -- unique user ID
    `client_title` VARCHAR(255), -- title e.g. Mr, Mrs, etc.
    `client_first_name` VARCHAR(255), -- first name
    `client_last_name` VARCHAR(255), -- last name
    `client_middle_name` VARCHAR(255), -- middle name
    `client_email_address` VARCHAR(255), -- email address
    `client_date_of_birth` VARCHAR(255), -- date of birth
    `client_ailment` VARCHAR(255), -- medical condition
    `client_primary_phone` VARCHAR(255), -- phone number
    `client_sexuality` VARCHAR(255), -- gender/sexuality
    `client_address_line_1` VARCHAR(255), -- house number
    `client_address_line_2` VARCHAR(255), -- street name
    `client_city` VARCHAR(255), -- city
    `client_county` VARCHAR(255), -- county
    `client_poster_code` VARCHAR(255), -- postal code
    `client_country` VARCHAR(255), -- country
    `client_access_details` VARCHAR(255), -- key safe or access details
    `client_highlights` VARCHAR(255), -- highlights
    `uryyToeSS4` VARCHAR(255), -- unique ID
    `what_is_important_to_me` VARCHAR(255), -- what is important to me
    `my_likes_and_dislikes` VARCHAR(255), -- my likes and dislikes
    `my_current_condition` VARCHAR(255), -- my current condition
    `my_medical_history` VARCHAR(255), -- my medical history
    `my_physical_health` VARCHAR(255), -- my physical health
    `my_mental_health` VARCHAR(255), -- my mental health
    `how_i_communicate` VARCHAR(255), -- how I communicate
    `assistive_equipment_i_use` VARCHAR(255), -- assistive equipment I use
    `client_latitude` VARCHAR(255), -- latitude
    `client_longitude` VARCHAR(255), -- longitude
    `col_pay_rate` VARCHAR(255), -- pay rate
    `col_qrcode_path` VARCHAR(255), -- QR code file path
    `geolocation` VARCHAR(255), -- geolocation string
    `qrcode` VARCHAR(255), -- QR code content
    `col_company_Id` VARCHAR(255), -- company ID
    `dateTime` VARCHAR(255), -- creation or update time
    PRIMARY KEY (`userId`)
);

CREATE TABLE `tbl_general_client_form` (
    `userId` VARCHAR(255), -- unique user ID
    `client_first_name` VARCHAR(255), -- first name
    `client_last_name` VARCHAR(255), -- last name
    `client_date_of_birth` VARCHAR(255), -- date of birth
    `uryyToeSS4` VARCHAR(255), -- unique ID
    `client_highlights` VARCHAR(255)
        PRIMARY KEY (`userId`)
);

CREATE TABLE `tbl_goesoft_carers_account` (
    `userId` VARCHAR(255), -- ID
    `user_fullname` VARCHAR(255), -- Full Name
    `user_email_address` VARCHAR(255), -- Email
    `user_phone_number` VARCHAR(255), -- Phone
    `user_password` VARCHAR(255), -- Password
    `col_cookies_identifier` VARCHAR(255), -- Cookie Identifier
    `user_special_Id` VARCHAR(255), -- Unique ID
    `col_company_Id` VARCHAR(255), -- Company ID
    PRIMARY KEY (`userId`)
);

CREATE TABLE `tbl_future_planning` (
    `userId` VARCHAR(255), -- ID
    `col_first_box` VARCHAR(255), -- Does he/she have capacity to make decisions related to their health and wellbeing?
    `col_second_box` VARCHAR(255), -- Health and Welfare LPA
    `col_third_box` VARCHAR(255), -- Property and Financial Affairs LPA
    `col_fourt_box` VARCHAR(255), -- Do Not Attempt Cardiopulmonary Resuscitation (DNACPR)
    `col_fift_box` VARCHAR(255), -- Advance Decision to Refuse Treatment (ADRT / Living Will)
    `col_sixth_box` VARCHAR(255), -- Recommended Summary Plan for Emergency Care and Treatment (ReSPECT)
    `col_seventh_box` VARCHAR(255), -- Where is it kept?
    `uryyToeSS4` VARCHAR(255), -- Client Special ID
    PRIMARY KEY (`userId`)
);

CREATE TABLE `tbl_client_medical` (
    `userId` int(255),
    `col_nhs_number` varchar(255),
    `col_medical_support` varchar(255),
    `col_dnar` varchar(255),
    `col_allergies` varchar(255),
    `col_gp_name` varchar(255),
    `col_phone_number` varchar(255),
    `gp_email_address` varchar(255),
    `gp_address` varchar(255),
    `col_pharmancy_name` varchar(255),
    `pharmacy_phone` varchar(255),
    `col_pharmancy_address` varchar(255),
    `col_swname` varchar(255) DEFAULT NULL,
    `col_swaddress` varchar(255) DEFAULT NULL,
    `col_swtelephone` varchar(255) DEFAULT NULL,
    `col_distnurse` varchar(255) DEFAULT NULL,
    `uryyToeSS4` varchar(255),
    `col_company_Id` varchar(255),
    `dateTime` varchar(255),
    sPRIMARY KEY (`userId`)
);

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
    `col_care_call_Id` VARCHAR(255), -- User ID(tbl_schedule_calls)
    `col_postcode` VARCHAR(255), -- client postal code
    `dateTime` VARCHAR(255), -- creation or update time
    PRIMARY KEY (`userId`)
);

-- This columns will update when the user checks out

-- Get userid from url tab. Use it to select pay_rate(column) and client_rate(column) from tbl_schedule_calls where userid(tbl_schedule_calls) = userid(from url tab).
-- Calculate the worked time by calculating the time difference between shift_start_time(column) and shift_end_time(column).
-- Calculate the col_client_payer by multiplying the worked_time(column) and client_rate(column).
-- Calculate the col_carecall_rate by multiplying the worked_time(column) and pay_rate(column).
-- Insert the current time into shift_end_time(column) when the user checks out.
CREATE TABLE `tbl_daily_shift_records` (
    `shift_end_time` VARCHAR(255), -- Insert the current time when the user checks out
    `task_note` VARCHAR(255), -- Insert the form note into this column when the user checks out
    `timesheet_date` VARCHAR(255), -- Insert the current date when the user checks out
    `col_carecall_rate` VARCHAR(255), -- Get userid from url tab
    `col_worked_time` VARCHAR(255), -- Insert total work time from time difference between shift_start_time and shift_end_time
    `col_client_rate` VARCHAR(255), -- Insert client_rate(column) from tbl_schedule_calls where userid(tbl_schedule_calls) = userid(from url tab)
    `col_client_payer` VARCHAR(255), -- Null(this will be updated when the user checks out)
    PRIMARY KEY (`userId`)
);

CREATE TABLE `tbl_daily_shift_records` (
    `userId` VARCHAR(255), -- User ID
    `shift_status` VARCHAR(255), -- Checked in (static string)
    `shift_date` VARCHAR(255), -- Shift Date (e.g. visits for the day)
    `planned_timeIn` VARCHAR(255), -- Planned Time In
    `planned_timeOut` VARCHAR(255), -- Planned Time Out
    `shift_start_time` VARCHAR(255), -- Actual check-in time (current time when user checks in)
    `shift_end_time` VARCHAR(255), -- Actual check-out time (NULL until checked out)
    `client_name` VARCHAR(255), -- Client Name
    `uryyToeSS4` VARCHAR(255), -- Unique client ID
    `col_care_call` VARCHAR(255), -- Care Calls (morning, lunch, tea, bed, extra calls, etc.)
    `client_group` VARCHAR(255), -- Client Area or group
    `carer_Name` VARCHAR(255), -- Carer Name
    `task_note` VARCHAR(255), -- Null (Notes related to tasks or shift (NULL until checked out))
    `col_carer_Id` VARCHAR(255), -- Carer Special ID
    `timesheet_date` VARCHAR(255), -- Null (Timesheet date (NULL until checked out))
    `col_area_Id` VARCHAR(255), -- Client Area ID
    `col_company_Id` VARCHAR(255), -- Company ID (string, not integer)
    `col_call_status` VARCHAR(255), -- Call Status (Scheduled, Not Completed, Completed)
    `col_carecall_rate` VARCHAR(255), -- Null (Rate per care call (NULL until checked out))
    `col_miles` VARCHAR(255), -- Miles calculated from client postcode to carer location
    `col_mileage` VARCHAR(255), -- Mileage rate (e.g., £0.45 per mile)
    `col_worked_time` VARCHAR(255), -- Null (Total worked time for shift (NULL until checked out))
    `col_client_rate` VARCHAR(255), -- Null (Rate per client (NULL until checked out))
    `col_client_payer` VARCHAR(255), -- Null (Client payer (NULL until checked out))
    `col_visit_status` VARCHAR(255), -- Visit status (static string, e.g., "True")
    `col_visit_confirmation` VARCHAR(255), -- Visit confirmation (static string, e.g., "Unconfirmed")
    `col_care_call_Id` VARCHAR(255), -- Care Call ID (from tbl_schedule_calls)
    `col_postcode` VARCHAR(255), -- Client postal code
    `dateTime` VARCHAR(255), -- Record creation or update timestamp
    PRIMARY KEY (`userId`)
);

--Client medication records
CREATE TABLE `tbl_clients_medication_records` (
    `user` VARCHAR(255), -- user ID
    `uryyToeSS4` VARCHAR(255), -- client Unique ID
    `med_name` VARCHAR(255), -- Medication Name
    `med_dosage` VARCHAR(255), -- Dosage
    `med_type` VARCHAR(255), -- Medication Type (e.g., tablet, syrup)
    `med_support_required` VARCHAR(255), -- Support Required for Medication
    `med_package` VARCHAR(255), -- Medication Package (e.g., blister Scheduled, PRN)
    `med_details` VARCHAR(255), -- Additional Medication Details
    `care_call1` VARCHAR(255), -- Care Call 1 (Morning)
    `care_call2` VARCHAR(255), -- Care Call 2 (Lunch)
    `care_call3` VARCHAR(255), -- Care Call 3 (Tea)
    `care_call4` VARCHAR(255), -- Care Call 4 (Bed)
    `extra_call1` VARCHAR(255), -- Extra Call 1 (EM morning call)
    `extra_call2` VARCHAR(255), -- Extra Call 2 (EL lunch call)
    `extra_call3` VARCHAR(255), -- Extra Call 3 (ET tea call)
    `extra_call4` VARCHAR(255), -- Extra Call 4 (EB bed call)
    `monday` VARCHAR(255), -- Monday
    `tuesday` VARCHAR(255), -- Tuesday
    `wednesday` VARCHAR(255), -- Wednesday
    `thursday` VARCHAR(255), -- Thursday
    `friday` VARCHAR(255), -- Friday
    `saturday` VARCHAR(255), -- Saturday
    `sunday` VARCHAR(255), -- Sunday
    `client_startMed` VARCHAR(255), -- Medication Start Date
    `client_endMed` VARCHAR(255), -- Medication End Date
    `col_taskId` VARCHAR(255), -- Meds ID (unique identifier for the task)
    `col_company_Id` VARCHAR(255), -- Company ID (string)
    PRIMARY KEY (`med_Id`)
);

--Client task records
CREATE TABLE `tbl_clients_task_records` (
    `userId` VARCHAR(255), -- User ID
    `uryyToeSS4` VARCHAR(255), -- unique ID
    `client_taskName` VARCHAR(255), -- Task Name
    `client_task_details` VARCHAR(255), -- Task Details
    `task_type` VARCHAR(255), -- Task Type (e.g. personal care,
    `care_call1` VARCHAR(255), -- Care Call 1(Morning)
    `care_call2` VARCHAR(255), -- Care Call 2(Lunch)
    `care_call3` VARCHAR(255), -- Care Call 3(Tea)
    `care_call4` VARCHAR(255), -- Care Call 4(Bed)
    `extra_call1` VARCHAR(255), -- Extra Call 1(EM morning call)
    `extra_call2` VARCHAR(255), -- Extra Call 2(EL lunch call)
    `extra_call3` VARCHAR(255), -- Extra Call 3(ET tea call)
    `extra_call4` VARCHAR(255), -- Extra Call 4(EB bed call)
    `monday` VARCHAR(255), -- Monday
    `tuesday` VARCHAR(255), -- Tuesday
    `wednesday` VARCHAR(255), -- Wednesday
    `thursday` VARCHAR(255), -- Thursday
    `friday` VARCHAR(255), -- Friday
    `saturday` VARCHAR(255), -- Saturday
    `sunday` VARCHAR(255), -- Sunday
    `task_startDate` VARCHAR(255), -- Task Start Date
    `task_endDate` VARCHAR(255), -- Task End Date
    `col_taskId` VARCHAR(255), -- Task ID(unique ID)
    `col_company_Id` VARCHAR(255), -- Company ID(this is not an integer it's a string)
    PRIMARY KEY (`client_Id`)
);

CREATE TABLE `tbl_finished_meds` (
    `userId` VARCHAR(255), -- User ID
    `meds` VARCHAR(255), -- Medication Name
    `med_date` VARCHAR(255), -- Date
    `timeIn` VARCHAR(255), -- Time In
    `note` VARCHAR(255), -- Note
    `uniqueId` VARCHAR(255), -- Unique ID(Meds ID)
    `uryyToeSS4` VARCHAR(255), -- Client Special ID
    `carer_Id` VARCHAR(255), -- Carer Special ID
    `carer_name` VARCHAR(255), -- Carer Name col_status
    `care_calls` VARCHAR(255), -- Care Calls
    `col_status` VARCHAR(255), -- Status (e.g. Completed, Refused, Not available or Not Completed)
    `col_company_Id` VARCHAR(255), -- Company ID(this is not an integer it's a string)
    `dateTime` VARCHAR(255), -- creation or update time
    PRIMARY KEY (`userId`)
);

CREATE TABLE `tbl_finished_tasks` (
    `userId` VARCHAR(255), -- User ID
    `task` VARCHAR(255), -- Task Name
    `task_date` VARCHAR(255), -- Date
    `timeIn` VARCHAR(255), -- Time In
    `note` VARCHAR(255), -- Note
    `uniqueId` VARCHAR(255), -- Unique ID(Task ID)
    `uryyToeSS4` VARCHAR(255), -- Client Special ID
    `carer_Id` VARCHAR(255), -- Carer Special ID
    `carer_name` VARCHAR(255), -- Carer Name
    `care_calls` VARCHAR(255), -- Care Calls
    `col_status` VARCHAR(255), -- Status (e.g. Completed, Refused, Not available or Not Completed)
    `col_company_Id` VARCHAR(255), -- Company ID(this is not an integer it's a string)
    `dateTime` VARCHAR(255), -- creation or update time
    PRIMARY KEY (`userId`)
);

--In all of them the date format is yyyy-mm-dd

'tbl_cancelled_call' => ['col_company_Id'], -- Also Select all where col_date = to current date for one month
    'tbl_clients_medication_records' => ['col_company_Id'], -- select all rows
    'tbl_client_status_records' => ['col_company_Id'], -- Also Select all where col_start_date = to current date for one month
    'tbl_clients_task_records' => ['col_company_Id'], -- select all rows
    'tbl_daily_shift_records' => ['col_company_Id', 'col_carer_Id'], -- Also Select all where shift_date = to current date(date table) two months(One month before and now)
    'tbl_finished_meds' => ['col_company_Id'], -- Also Select all where med_date = to current date(date table) two months(One month before and now)
    'tbl_finished_tasks' => ['col_company_Id'], -- Also Select all where task_date = to current date(date table) two months(One month before and now)
    'tbl_general_client_form' => ['col_company_Id'], -- select all rows
    'tbl_client_medical' => ['col_company_Id'], -- select all rows
    'tbl_future_planning' => ['col_company_Id'],    -- select all rows
    'tbl_schedule_calls' => ['col_company_Id'], -- Also Select all where Clientshift_Date = to current date(date table) two months(One month before and now)
    'tbl_general_team_form' => ['col_company_Id'],  -- select all rows
    'tbl_manage_runs' => ['col_company_Id'] -- select all rows




    CREATE TABLE `tbl_cancelled_call` (
    `userId` VARCHAR(255),
    `col_care_call` VARCHAR(255), -- Care Calls (e.g. morning, lunch, tea, bed, extra morning, extra lunch, extra tea, extra bed)
    `col_client_Id` VARCHAR(255), -- client unique ID(which is uryyToeSS4 in tbl_schedule_calls)
    `col_date` VARCHAR(255), -- Date of cancellation
    PRIMARY KEY (`userId`)
);

CREATE TABLE `tbl_client_status_records` (
    `userId` VARCHAR(255),
    `col_start_date` VARCHAR(255), -- Start date of the status
    `col_end_date` VARCHAR(255), -- End date of the status
    `col_client_Id` VARCHAR(255), -- client unique ID(which is uryyToeSS4 in tbl_schedule_calls)
    PRIMARY KEY (`userId`)
);