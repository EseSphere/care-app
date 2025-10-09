CREATE TABLE `tbl_schedule_calls` (
    /* User ID */
    `userId` VARCHAR(255),
    /* Client Name */
    `client_name` VARCHAR(255),
    /* Client Area */
    `client_area` VARCHAR(255),
    /* Carer name */
    `first_carer` VARCHAR(255),
    /* Carer special ID */
    `first_carer_Id` VARCHAR(255),
    /* Care Calls (e.g. morning, lunch, tea, bed, extra morning, extra lunch, extra tea, extra bed) */
    `care_calls` VARCHAR(255),
    /* Time In */
    `dateTime_in` VARCHAR(255),
    /* Time Out */
    `dateTime_out` VARCHAR(255),
    /* Run Name */
    `col_run_name` VARCHAR(255),
    /* Number of Carers Required e.g 1 or 2 */
    `col_required_carers` VARCHAR(255),
    /* Shift Date e.g visits for the day */
    `Clientshift_Date` VARCHAR(255),
    /* Call Status e.g Scheduled, Not completed or Completed */
    `call_status` VARCHAR(255),
    PRIMARY KEY (`userId`)
);

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

CREATE TABLE `tbl_goesoft_carers_account` (
    `userId` VARCHAR(255),
    /*id*/
    `user_fullname` VARCHAR(255),
    /*full name*/
    `user_email_address` VARCHAR(255),
    /*Email*/
    `user_phone_number` VARCHAR(255),
    /*phone*/
    `user_password` VARCHAR(255),
    /*password*/
    `col_cookies_identifier` VARCHAR(255),
    /*cookie*/
    `user_special_Id` VARCHAR(255),
    /*uniqueId*/
    `col_company_Id` VARCHAR(255),
    /*CompanyId*/
    PRIMARY KEY (`userId`)
);