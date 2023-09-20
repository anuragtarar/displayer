<?php
	
	// JOB LISTING ID ...
	$jobListingId = $jobinfo['id'];
	// JOB LISTING TYPE ...
	$jobListingType = $jobinfo['jobtype'];		
	// JOB COMPANY ...
	$jobCompany = $jobinfo['company'];
	if (!$jobCompany) {
	   $jobCompany = 'Confidential';
	}
	$jobCompany = str_replace(array('{xkeywordurl}','{KEYWORD}'),ucwords($keyword),$jobCompany);
	// JOB CITY ...
	$jobCity = $jobinfo['city'];
	if(!$jobCity) { // if `job city` not found
		$jobCity = $city;
	}
	if(strtolower($jobCity) == 'vhmnetwork') { // Vhmnetwork
		$jobCity = 'All Cities'; 
	}
	if(strtolower($jobCity) == 'nationwide' || strtolower($jobCity) == 'nation wide') { // Nationwide
		$jobCity = $city;
	}	
	$jobCompany = str_replace('(City)',ucwords($jobCity),$jobCompany);
	$jobCompany = str_replace('{LOCATION}',ucwords($location),$jobCompany);
	// JOB STATE ...
	$jobState = $jobinfo['state'];
	if(!$jobState) { // if `job state` not found
		$jobState = $state;
	}
	if(strtolower($jobState) == 'nw') { // NW
		$jobState = $state;
	}	
	// JOB TITLE ...	
	$jobTitle = $jobinfo['title'];
	if(strtolower($aff_id) == 'uspath' && strtolower($sub_id) == 'google_new_young') {
		// replace {Keyword}, {keyword} and {xkeyword} from the job title
		$jobTitle = str_replace(array('{Keyword}','{keyword}','{xkeyword}','(keyword)','{KEYWORD}'), "Food & Hospitality", $jobTitle);	
	}	
	else {
		// replace {Keyword}, {keyword} and {xkeyword} from the job title
		$jobTitle = str_replace(array('{Keyword}','{keyword}','{xkeyword}','(keyword)','{KEYWORD}'), ucwords($keyword), $jobTitle);	
	}
	$jobTitle = str_ireplace('{City}', $jobCity, $jobTitle); // replace {City} from the job title
	$jobTitle = str_replace(array('{location}','{Location}','{LOCATION}'),ucwords($location),$jobTitle); // replace {location} from the job title
	// JOB COUNTRY ...
	$jobCountry = $jobinfo['country'];
	// JOB ZIPCODE ...
	$jobZipCode = $jobinfo['zip'];
	// JOB SPONSOR NAME ...
	$jobSponsorName = $jobinfo['name'];	
	// JOB SPONSOR REFERENCE ...
	$jobSponsorReference = $jobinfo['reference'];
	if(!$jobSponsorName) { // if `sponsor name` not found
		$jobSponsorName = $jobSponsorReference;
	}
	// JOB RANKING ...
	$jobRanking = $jobinfo['rank'];
	// JOB PAYOUT ...
	$jobPayout = $jobinfo['payout'];
	// JOB CATEGORY ID ...
	if(strtolower($aff_id) == 'uspath' && strtolower($sub_id) == 'google_new_young') {
		$jobCategoryID = 23;
	}
	else {
		$jobCategoryID = $jobinfo['categoryId'];
	}
	// JOB CATEGORY NAME ...
	if (is_numeric($jobCategoryID))	{ 				
		if (array_key_exists($jobCategoryID, $apiconfig['allowedCountries'][$siteid]['jobcat'])) {
			$categoryName = $apiconfig['allowedCountries'][$siteid]['jobcat'][$jobCategoryID];
		}
	}
	// JOB CREATED DATE ...
	$jobCreatedDate = date('Y-m-d', strtotime($jobinfo['insertdate']));
	if(!$jobCreatedDate) {
		$jobCreatedDate = date('Y-m-d'); 
		if ($siteid == 'FR') {
			$jobCreatedDate = date("D, d/m/Y");
		}	
	}
	else {
		if ($siteid == 'FR') {
			$jobCreatedDate = date("D, d/m/Y", strtotime($jobCreatedDate));
		}
	}
	// JOB DESCRIPTION ...
	switch (strtolower($aff_id)) {
		// ea_Mnet_api					
		case 'ea_mnet_api':
			$jobShortDescription = $jobinfo['description']; // Media.net wants us to test including full descriptions.  
			$jobShortDescription = strip_tags(str_replace("<br/>",' ',$jobShortDescription));
			$jobShortDescription = str_replace(array('{Keyword}','{keyword}','{xkeyword}','{KEYWORD}'),ucwords($keyword),$jobShortDescription); // replace {Keyword}, {keyword} and {xkeyword} from the job title
			$jobShortDescription = str_replace(array('{Location}','{LOCATION}'),ucwords($location),$jobShortDescription); // replace {location} from the job description
			$jobShortDescription = ellipsis($jobShortDescription, 200);
		break;
		
		default:
			$jobShortDescription = $jobinfo['short_description'];
			$jobShortDescription = strip_tags(str_replace("<br/>",' ',$jobShortDescription));
			$jobShortDescription = str_replace(array('{Keyword}','{keyword}','{xkeyword}','{KEYWORD}'),ucwords($keyword),$jobShortDescription); // replace {Keyword}, {keyword} and {xkeyword} from the job title
			$jobShortDescription = str_replace(array('{Location}','{LOCATION}'),ucwords($location),$jobShortDescription); // replace {location} from the job description
			$jobShortDescription = str_replace('{City}',ucwords($city),$jobShortDescription); // replace {city} from the job description
			$jobShortDescription = ellipsis($jobShortDescription, 145);
	}		 	
	
	
	if($siteid == 'US') { // FOR US SITE .....
	//switch ($siteid) {
		//case 'US':	// FOR US SITE .....
			switch (strtolower($jobSponsorName)) {
				// topresume	
				case 'topresume':
					$jobTitle = str_replace(' .','.',$jobTitle);
				break;
				// JobcaseDynamic25					
				case 'jobcasedynamic25':
					$jobCompany = ucwords($keyword).'.'.$jobCompany; // add `keyword` in the `job company`
					$jobShortDescription .= $location; // add `location` at last in the `job description`
				break;
				// CareerHunter	
				// JobOwl					
				case 'careerhunter':
				case 'jobowl':
					$jobTitle .= ' '.$zip_code; 
					$jobShortDescription .= ' '.$zip_code; // add `zipcode` at last in the `job description`
				break;
				// silvertapstatic	
				case 'silvertapstatic':
					$jobTitle = ucwords($keyword).' '.$jobTitle; // add `keyword` in the `job title`
					$jobCompany = ucwords($keyword).'.'.$jobCompany; // add `keyword` in the `job company`
				break;
				// MovingUpCareers	
				case 'movingupcareers':
					$jobTitle = $location.' '.$jobTitle; // add `location` in the `job title`
				break;
			}
			switch (strtolower($jobSponsorReference)) {
				// JobOwl20					
				case 'jobowl':
					$jobTitle .= ' '.$zip_code; 
					$jobShortDescription .= ' '.$zip_code; // add `zipcode` at last in the `job description`
				break;
				// MyJobsCorner30	
				case 'myjobscorner':
					$jobTitle = str_replace('Generic',ucwords($keyword),$jobTitle);
					$jobCompany = str_replace('Generic','MyJobsCorner',$jobCompany);
				break;
			}			
			// Publisher `CPC`
			$apiCPCValue = '';
			switch (strtolower($aff_id)) {
				/** 
				* `ea_cmp_api`
				* `ea_adbrilliant_api`
				* `ea_jobsgalore_api`
				* `ea_red3i_api`
				* `ea_AdMediary_api`
				* `ea_notifyAI_api`
				* `ea_Aimtell_api`
				* `ea_717ads_api`
				* `ea_pushnamiEmail_api`
				* `ea_botson_api`
				*/
				case 'ea_cmp_api':
				case 'ea_adbrilliant_api':
				case 'ea_jobsgalore_api':
				case 'ea_red3i_api':
				case 'ea_notifyai_api':
				case 'ea_aimtell_api':
				case 'ea_717ads_api':
				case 'ea_dms_api':
					// assign CPC's
					$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
				break;
				/** 
				* `ea_adbrilliant_api2`
				*/	
				case 'ea_adbrilliant_api2':
					// assign CPC's			
					$apiCPCValue = round(($jobPayout*0.80),2); // Pass CPC's as 80% of the Admin CPC
				break;	
				/** 
				* `ea_pushnamiEmail_api`
				*/	
				case 'ea_pushnamiemail_api':		
					// assign CPC's
					if($value != 'JODdynamicListing') {
						// Pass the minimum CPC of $0.15
						if($value != 'dynamicAdListing') {
							$apiCPCValue = 0.12; 
						}
					}
				break;	
				/** 
				* `ea_botson_api`
				*/	
				case 'ea_botson_api':
					// assign CPC's			
					$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
					// Set a minimum passed bid of $0.25
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.25) {
							$apiCPCValue = 0.25; 
						}
					}
				break;
				/** 
				* `ea_apptness_api`
				*/	
				case 'ea_apptness_api':
					// assign CPC's			
					$apiCPCValue = round(($jobPayout*0.70),2); // Pass CPC's as 70% of the Admin CPC
					// Set a minimum passed bid of $0.35
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.35) {
							$apiCPCValue = 0.35; 
						}
					}
				break;					
				/** 
				* `ea_vicero_api`
				*/	
				case 'ea_vicero_api':
					// assign CPC's	
					if($value != 'JODdynamicListing') {		
						$apiCPCValue = round(($jobPayout*0.70),2); // Pass CPC's as 70% of the Admin CPC
						// Set a minimum passed bid of $0.21
						if($value != 'dynamicAdListing') {
							if($apiCPCValue < 0.21) {
								$apiCPCValue = 0.21; 
							}
						}
					}
					// FOR JOD Dynamic Listing
					if($value == 'JODdynamicListing') {
						$apiCPCValue = 0.20; // Make live for ea_vicero_api ($0.20 CPC).  
					}
				break;
				/** 
				* `ea_connexus_api`
				*/	
				case 'ea_connexus_api':
					// assign CPC's			
					$apiCPCValue = round(($jobPayout*0.70),2); // Pass CPC's as 70% of the Admin CPC
					// Set a minimum passed bid of $0.28
					if($value != 'radiusMatchWCPCF' && $value != 'radiusMatchWCPCAJF') { // there is no minimum passed bid for fallback jobs. 
						if($apiCPCValue < 0.24) {
							$apiCPCValue = 0.24; 
						}
					}
				break;
				/** 
				* `ea_LocalStaffing_api`
				*/	
				case 'ea_localstaffing_api':
					// assign CPC's			
					$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
					// Set a minimum passed bid of $0.35
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.35) {
							$apiCPCValue = 0.35; 
						}
					}
				break;
				/** 
				* `ea_threehyphens_api`
				*/	
				case 'ea_threehyphens_api':
					// assign CPC's			
					$apiCPCValue = round(($jobPayout*0.70),2); // Pass CPC's as 70% of the Admin CPC
					// Set a minimum passed bid of $0.15
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.15) {
							$apiCPCValue = 0.15; 
						}
					}
				break;
				/** 
				* `ea_lp_api`
				*/
				case 'ea_lp_api':
					// assign CPC's
					$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
					// Pass the minimum CPC of $0.15
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.15) {
							$apiCPCValue = 0.15; 
						}
					}
				break;				
				/** 
				* `ea_pushnami_api`
				*/
				case 'ea_pushnami_api': 
					// assign CPC's
					if($value != 'JODdynamicListing') {
						// Pass the minimum CPC of $0.15
						if($value != 'dynamicAdListing') {
							$apiCPCValue = 0.12; 
						}
					}
				break;	
				/** 
				* `ea_AdMediary_api`
				*/
				case 'ea_admediary_api':
					// assign CPC's
					$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
					// Pass the minimum CPC of $0.20
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.20) {
							$apiCPCValue = 0.20; 
						}
					}
				break;		
				/** 
				* `ea_Mnet_api`
				*/
				case 'ea_mnet_api':					
					// assign CPC's
					if($value != 'JODdynamicListing') {
						$apiCPCValue = round(($jobPayout*0.80),2); // Pass CPC's as 80% of the Admin CPC
						// Pass the minimum CPC of $0.34
						if($value != 'dynamicAdListing') {
							if($apiCPCValue < 0.34) {
								$apiCPCValue = 0.34; 
							}
						}
					}
					// FOR JOD Dynamic Listing
					if($value == 'JODdynamicListing') {
						$apiCPCValue = 0.15; // Make live for `ea_Mnet_api` ($0.15 CPC).  
					}
					// JODDynamicInterstitial
					if($jobSponsorReference=='JODDynamicInterstitial') {
						$apiCPCValue = 0.18;
					}
				break;	
				/** 
				* `ea_jobsathomestaffing_api`
				*/
				case 'ea_jobsathomestaffing_api':					
					// JODDynamicInterstitial
					if($jobSponsorReference=='JODDynamicInterstitial') {
						$apiCPCValue = 0.15;
					}
				break;		
				/** 
				* `ea_localstaffing_featured`
				*/
				case 'ea_localstaffing_featured':
					// assign CPC's
					$apiCPCValue = round(($jobPayout*0.70),2); // Pass CPC's as 70% of the Admin CPC
					// Pass the minimum CPC of $0.15
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.15) {
							$apiCPCValue = 0.15; 
						}
					}
				break;			
				/** 
				* `ea_silvertap_serp`
				*/
				case 'ea_silvertap_serp':
					// assign CPC's
					$apiCPCValue = round(($jobPayout*0.70),2); // Pass CPC's as 70% of the Admin CPC
					// Pass the minimum CPC of $0.20
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.20) {
							$apiCPCValue = 0.20;
						}
					}
				break;		
				/** 
				* `ea_cmg_api`
				*/
				case 'ea_cmg_api':
					// assign CPC's
					if($value != 'JODdynamicListing') {
						$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
						// Pass the minimum CPC of $0.28
						if($value != 'dynamicAdListing') {
							if($apiCPCValue < 0.28) {
								$apiCPCValue = 0.28;
							}
						}
					}
					// FOR JOD Dynamic Listing
					if($value == 'JODdynamicListing') {
						$apiCPCValue = 0.20; // Make live for ea_CMG_api ($0.20 CPC).  
					}
				break;	
				/** 
				* `ea_silvertap_email`
				* `ea_silvertap_sms`
				*/
				case 'ea_silvertap_email':
				case 'ea_silvertap_sms':
					// assign CPC's
					$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
					// Pass the minimum CPC of $0.14
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.14) {
							$apiCPCValue = 0.14;
						}
					}
				break;	
				/** 
				* `ea_results_api`
				*/	
				case 'ea_results_api':
				case 'ea_brightfire_api':
					// assign CPC's			
					$apiCPCValue = round(($jobPayout*0.70),2); // Pass CPC's as 70% of the Admin CPC
					// Set a minimum passed bid of $0.20
					if($value != 'dynamicAdListing') {
						if($apiCPCValue < 0.20) {
							$apiCPCValue = 0.20; 
						}
					}
					// FOR JOD Dynamic Listing
					if($value == 'JODdynamicListing') {
						$apiCPCValue = 0.20; // Make live for ($0.20 CPC).  
					}
				break;
			}
			
			/**
			* DirectLink
			===============
			*/
			###
			$aff_id_interstital = $aff_id;
			if($IS_JODDynamicInterstitialPage) {
				$aff_id_interstital = $aff_id.'_interstitial';
			}
			$jobDirectLink = "https://www.employmentalert.com/api/viewjob.php?id=".$jobListingId.$jobListingType."&siteid=".$siteid."&aff_id=".$aff_id_interstital."&sub_id=".$sub_id."&keyword=".urlencode($keyword)."&sub_category=".urlencode($sub_category_track)."&location=".urlencode($location)."&city=".urlencode($city)."&state=".$state."&zip=".$zip_code.'&utm_source='.urlencode($affid_numeric).'&utm_medium='.urlencode($utm_medium).'&utm_campaign='.urlencode($utm_campaign)."&t1=".$t1."&traffic=eaapi";
			###
			// You're passing Sales to our partners and have 24 as the category.  Both of these should be "Food & Hospitality" (category 23). 
			if(strtolower($aff_id) == 'uspath' && strtolower($sub_id) == 'google_new_young') {
				$jobDirectLink = "https://www.employmentalert.com/api/viewjob.php?id=".$jobListingId.$jobListingType."&siteid=".$siteid."&aff_id=".$aff_id."&sub_id=".$sub_id."&keyword=".urlencode($categoryName)."&sub_category=".urlencode($jobCategoryID)."&location=".urlencode($location)."&city=".urlencode($city)."&state=".$state."&zip=".$zip_code.'&utm_source='.urlencode($affid_numeric).'&utm_medium='.urlencode($utm_medium).'&utm_campaign='.urlencode($utm_campaign)."&t1=".$t1."&traffic=eaapi";
			}	
			###
					
			// append `cpc` in the `job direct link`
			if($apiCPCValue) {
				$jobDirectLink .= "&cpc=".$apiCPCValue;
			}			
			// append `original_date` for `alerts_sms_api` in the `job direct link`
			if(strtolower($aff_id) == 'alerts_sms_api') { 
				$jobDirectLink .= "&original_date=".$today;
			}
			if (isset($post['trigger'])) { // append `&trigger=sms` 2021-07-23 for `Renu mam`
				$jobDirectLink .= "&trigger=".$post['trigger'];
			}
			// append `creativeName` for `alerts_sms_api` in the `job direct link`
			if (isset($post['creativeName'])) { 
				$jobDirectLink .= "&creativeName=".$post['creativeName'];
			}
			// append `creativeNameSource` for `alerts_sms_api` in the `job direct link`
			if (isset($post['creativeNameSource'])) { 
				$jobDirectLink .= "&creativeNameSource=".$post['creativeNameSource'];
			}
			// append `creative` for `creative` in the `job direct link`
			if (isset($post['creative'])) { 
				$jobDirectLink .= "&creative=".$post['creative'];
			}
			// append `creative_part` for `creative` in the `job direct link`
			if (isset($post['creative_part'])) { 
				$jobDirectLink .= "&creative_part=".$post['creative_part'];
			}
			// append `phoneNumber` for `alerts_sms_api` in the `job direct link`
			if (isset($post['phoneNumber'])) {
				$jobDirectLink .= "&phoneNumber=".$post['phoneNumber'];
			}
			// append `myid` for `alerts_sms_api` in the `job direct link`
			if (isset($post['myid'])) { 
				$jobDirectLink .= "&myid=".$post['myid'];
			}			
			// append `jobposition` for Push Interstitial Affiliates
			if($IS_JODDynamicInterstitialPageQuery) {
				$jobDirectLink .= "&jbp=".$ambarCountMain;
				$jobDirectLink .= "&original_date=".$today;
				$jobDirectLink .= "&ts=".date('H:i:s');
				$jobTitle = $jobTitle." - #". rand();
				$jobCompany = $jobCompany." - #". rand();
				$jobCreatedDate = $today;
			}
			
			###
			// Masking the `ea_CMG_api` JOB URL
			if(strtolower($aff_id) == 'ea_cmg_api') {
				$jobDirectLink = "https://www.employmentalert.com/api/jbs.php?".bin2hex(Encode("id=".$jobListingId.$jobListingType."&siteid=".$siteid."&aff_id=".$aff_id."&sub_id=".$sub_id."&keyword=".urlencode($keyword)."&sub_category=".urlencode($sub_category_track)."&location=".urlencode($location)."&city=".urlencode($city)."&state=".$state."&zip=".$zip_code."&utm_source=".urlencode($affid_numeric)."&utm_medium=".urlencode($utm_medium)."&utm_campaign=".urlencode($utm_campaign)."&cpc=".$apiCPCValue."&t1=".$t1."&traffic=eaapi", "featured"));
			}
			###			
			
			if(strpos($aff_id, "_carousel") !== false){  
				$jobTitle = ellipsis($jobTitle, 40);
				$jobShortDescription = ellipsis($jobShortDescription, 100);
			}
	
			// Start a element
			$xml->startElement('JOB');
			$xml->writeElement('id', $jobListingId);	
			$xml->writeElement('title', $jobTitle);
			$xml->writeElement('company', $jobCompany);
			$xml->writeElement('description', $jobShortDescription);
			$xml->writeElement('url', $jobDirectLink);
			$xml->writeElement('location', $jobCity);
			$xml->writeElement('state', $jobState);
			$xml->writeElement('country', $jobCountry);					
			$xml->writeElement('category', $categoryName);
			$xml->writeElement('categoryId', $jobCategoryID);
			$xml->writeElement('created_date', $jobCreatedDate);
			switch (strtolower($aff_id)) {
				// add `displayURL` XML tag for `ea_mnet_api`					
				case 'ea_mnet_api':
					// JOB URL ...
					$jobListingURL = $jobinfo['URL'];
					$xml->writeElement('displayURL', parse_url($jobListingURL, PHP_URL_HOST));
					// LOOGS ... Just use <logo> for the tag
					// JOB CATEGORY NAME ...
					if (is_numeric($jobCategoryID))	{ 				
						if (array_key_exists($jobCategoryID, $apiconfig['logos'])) {
							$categoryLogo = $apiconfig['logos'][$jobCategoryID];
						}
					}
					$xml->writeElement('logo', 'https://www.employmentalert.com/logos/'.$categoryLogo);
				break;
				// Are we able to pass the logos we use on search pages with each job per the below?
				case 'ea_notifyai_api':
					// LOOGS ... Just use <logo> for the tag
					// JOB CATEGORY NAME ...
					if (is_numeric($jobCategoryID))	{ 				
						if (array_key_exists($jobCategoryID, $apiconfig['logos'])) {
							$categoryLogo = $apiconfig['logos'][$jobCategoryID];
						}
					}
					$xml->writeElement('logo', 'https://www.employmentalert.com/logos/'.$categoryLogo);
				break;
				// add `shortURL` XML tag for `alerts_trumpia`					
				case 'alerts_trumpia':
					$sURL = new Shortener();
					$shortCode = $sURL->createShortCode($jobDirectLink);
					$xml->writeElement('shortURL', 'http://jb-d.com/'.$shortCode);
				break;
			}
			if($apiCPCValue) {
				$xml->writeElement('cpc', $apiCPCValue);
			}
			// For Personal use
			if ((isset($post['spninfo'])=="yes") || ($ip_address=='65.0.157.221') || (isset($post['JODad_'])=="JODad")) {
				$xml->writeElement('name', $jobSponsorName);
				$xml->writeElement('reference', $jobSponsorReference);
				$xml->writeElement('payout', $jobPayout);
				$xml->writeElement('rank', $jobRanking);
				$xml->writeElement('redis', $_aero);
				$xml->writeElement('match', $value);
				$xml->writeElement('jobposition', $ambarCountMain);
				$xml->writeElement('jobtype', $jobListingType);
			}		
			$xml->endElement();   /* </JOB> */
		//break;					
	
		}
		else { // FOR INTL SITES .....		
		//default: // FOR INTL SITES .....
		
			// Displaying the location based on the postcode
			if ($siteid == 'UK' || $siteid == 'CA') {
				// JOB ZIPCODE ...
				$jobZipode = $jobinfo['zip'];
				if($jobZipode) {
					$_job_location_by_zip_code = '{
					  "query": {
						"bool": {
						  "must": [
							{
							  "term": {
								"zipcode": "'.$jobZipode.'"
							  }
							},
							{
							  "term": {
								"country": "'.$siteid.'"
							  }
							}
						  ]
						}
					  }
					}';
					// POST DATA TO ES
					$result_job_location_by_zip_code = postJSONCurl('http://'.ES_MAILING_CLUSTER.':9200/intl_zip_code/_doc/_search?size=1', $_job_location_by_zip_code, 'http://'.ES_MAILING_CLUSTER.':9200/intl_zip_code/_doc/_search?size=1');
					// GET DATA FROM ES
					$result_job_location_by_zip_code = json_decode($result_job_location_by_zip_code, true);
					if ($result_job_location_by_zip_code['hits']['total']['value'] > 0) {
						$jobCity = $result_job_location_by_zip_code['hits']['hits'][0]['_source']['city']; // `city`
						$jobState = $result_job_location_by_zip_code['hits']['hits'][0]['_source']['state']; // `state`
						if(!$jobState) { // if `job state` not found
							$jobState = $state;
						}
						if(strtolower($jobState) == 'nw') { // NW
							$jobState = $state;
						}
					}
				}
			}
			
			// Add extension in `job title` and `job description` for `Airtasker`
			if(strtolower($jobSponsorName) == 'airtasker') { // Airtasker
				$jobTitleExtension = '';
				$jobShortDescriptionExtension = 'workers!';
				if($jobcat == 33) { 
					$jobTitleExtension = 'Drivers - '; 
					$jobShortDescriptionExtension = 'Drivers!';
				}
				else {
					if($jobcat == 31 || strtolower($keyword) == 'trade / handymen') { 
						$jobTitleExtension = 'Handymen - '; 
						$jobShortDescriptionExtension = 'Handymen!';
					}
					else {
						if(strtolower($keyword) == 'cleaners' || strtolower($keyword) == 'cleaner') {
							$jobTitleExtension = 'Cleaners - '; 
							$jobShortDescriptionExtension = 'Cleaners!';
						}
					}
				}		
				$jobTitle = $jobTitleExtension.'Earn over $1,000 a week completing tasks.';
				$jobShortDescription = 'Airtasker is seeking '.$jobShortDescriptionExtension.' With the ability to pick and choose jobs around your schedule, you can earn over $1000 per week. Browse relevant jobs in your area.';
			}
			// Start a element
			$xml->startElement('JOB');
			
			########
			// Publisher `CPC`
			$apiCPCValue = '';
			########
			if(($aff_id == 'ea_silvertap_api_ca') || (($aff_id == 'ea_botson_api') && ($siteid == 'CA'))) {				
				$currency_type = '';
				$_qry_cpc_details = '{
				  "query": {
					"bool": {
					  "must": [
						{
						  "term": {
							"sponsor": "'.$jobSponsorName.'"
						  }
						},
						{
						  "term": {
							"country": "'.$siteid.'"
						  }
						}
					  ]
					}
				  }
				}';
				$result_cpc_details = postJSONCurl('http://'. ES_MAILING_CLUSTER.':9200/jos_cpc_details_new_intl/_doc/_search?size=1', $_qry_cpc_details, 'http://'. ES_MAILING_CLUSTER.':9200/jos_cpc_details_new_intl/_doc/_search?size=1');
				$result_cpc_details = json_decode($result_cpc_details, true);	
				// Fetch data
				if(count($result_cpc_details['hits']['hits']) > 0) {
					for($ic = 0; $ic < count($result_cpc_details['hits']['hits']); $ic++) {
						$new_currency_type = trim($result_cpc_details['hits']['hits'][$ic]['_source']['new_currency_type']);
						$currency_type = trim($result_cpc_details['hits']['hits'][$ic]['_source']['currency_type']);
					}
				}
				if($new_currency_type) {
					// Assign `CPC' based on `New Currency Type`
					switch (strtolower($new_currency_type)) {
						// Pass CPC's for this aff_id as 65% of the admin CPC for USD bids					
						case 'us':
							$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
							//$xml->writeElement('cpc', $apiCPCValue);
						break;
						
						case 'ca':
							$apiCPCValue = round(($jobPayout*0.45),2); // Pass CPC's as 45% of the Admin CPC
							//$xml->writeElement('cpc', $apiCPCValue);
						break;
					}					
					// For "ea_botson_api" (siteid=CA)
					if(($aff_id == 'ea_botson_api') && ($siteid == 'CA')) {
						// Set a minimum passed bid of $0.12
						if($value != 'dynamicAdListing') {
							if($apiCPCValue < 0.12) {
								$apiCPCValue = 0.12; 
							}
						}
					}
					$xml->writeElement('cpc', $apiCPCValue);
				}
				else {
					// Assign `CPC' based on `Currency Type`
					if($currency_type) {
						switch (strtolower($currency_type)) {
							// Pass CPC's for this aff_id as 65% of the admin CPC for USD bids					
							case 'us':
								$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
								//$xml->writeElement('cpc', $apiCPCValue);
							break;
							
							case 'ca':
								$apiCPCValue = round(($jobPayout*0.45),2); // Pass CPC's as 45% of the Admin CPC
								//$xml->writeElement('cpc', $apiCPCValue);
							break;
						}
						// For "ea_botson_api" (siteid=CA)
						if(($aff_id == 'ea_botson_api') && ($siteid == 'CA')) {
							// Set a minimum passed bid of $0.12
							if($value != 'dynamicAdListing') {
								if($apiCPCValue < 0.12) {
									$apiCPCValue = 0.12; 
								}
							}
						}
						$xml->writeElement('cpc', $apiCPCValue);
					}
				}
			}
			########
			
			########
			// 1. Start passing CPC's as 65% of USD Admin CPC's and 95% of GBP Admin CPC's
			// 2. For `ea_connexus_api_uk`, start passing CPC's.  Pass as 75% of GBP bids and 60% of USD bids. 2023/06/09
			if(($aff_id == 'ea_connexus_api_uk') || ($aff_id == 'ea_pushnami_api_uk') || (($aff_id == 'ea_botson_api') && ($siteid == 'UK')))
			{			
				$currency_type = '';
				$_qry_cpc_details = '{
				  "query": {
					"bool": {
					  "must": [
						{
						  "term": {
							"sponsor": "'.$jobSponsorName.'"
						  }
						},
						{
						  "term": {
							"country": "'.$siteid.'"
						  }
						}
					  ]
					}
				  }
				}';
				$result_cpc_details = postJSONCurl('http://'. ES_MAILING_CLUSTER.':9200/jos_cpc_details_new_intl/_doc/_search?size=1', $_qry_cpc_details, 'http://'. ES_MAILING_CLUSTER.':9200/jos_cpc_details_new_intl/_doc/_search?size=1');
				$result_cpc_details = json_decode($result_cpc_details, true);	
				// Fetch data
				if(count($result_cpc_details['hits']['hits']) > 0) {
					for($il = 0; $il < count($result_cpc_details['hits']['hits']); $il++) {
						$new_currency_type = trim($result_cpc_details['hits']['hits'][$il]['_source']['new_currency_type']);
						$currency_type = trim($result_cpc_details['hits']['hits'][$il]['_source']['currency_type']);						
					}
				}
				if($new_currency_type) {
					// Assign `CPC' based on `New Currency Type`
					switch (strtolower($new_currency_type)) {
						// Pass CPC's for this aff_id as 65% of the admin CPC for USD bids					
						case 'us':
							$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
							if($aff_id == 'ea_connexus_api_uk') {
								$apiCPCValue = round(($jobPayout*0.60),2); // Pass as 60% of USD bids
							}
						break;
						
						case 'pound':
							$apiCPCValue = round(($jobPayout*0.95),2); // Pass CPC's as 45% of the Admin CPC
							if($aff_id == 'ea_connexus_api_uk') {
								$apiCPCValue = round(($jobPayout*0.75),2); // Pass as 75% of GBP bids
							}
						break;
					}
					// For "ea_botson_api" (siteid=UK)
					if(($aff_id == 'ea_botson_api') && ($siteid == 'UK')) {
						// Set a minimum passed bid of $0.16
						if($value != 'dynamicAdListing') {
							if($apiCPCValue < 0.16) {
								$apiCPCValue = 0.16; 
							}
						}
					}
					$xml->writeElement('cpc', $apiCPCValue);
				}
				else {
					if($currency_type) {
						// Assign `CPC' based on `Currency Type`
						switch (strtolower($currency_type)) {
							// Pass CPC's for this aff_id as 65% of the admin CPC for USD bids					
							case 'us':
								$apiCPCValue = round(($jobPayout*0.65),2); // Pass CPC's as 65% of the Admin CPC
								if($aff_id == 'ea_connexus_api_uk') {
									$apiCPCValue = round(($jobPayout*0.60),2); // Pass as 60% of USD bids
								}
							break;
							
							case 'pound':
								$apiCPCValue = round(($jobPayout*0.95),2); // Pass CPC's as 45% of the Admin CPC
								if($aff_id == 'ea_connexus_api_uk') {
									$apiCPCValue = round(($jobPayout*0.75),2); // Pass as 75% of GBP bids
								}
							break;
						}
						// For "ea_botson_api" (siteid=UK)
						if(($aff_id == 'ea_botson_api') && ($siteid == 'UK')) {
							// Set a minimum passed bid of $0.16
							if($value != 'dynamicAdListing') {
								if($apiCPCValue < 0.16) {
									$apiCPCValue = 0.16; 
								}
							}
						}
						$xml->writeElement('cpc', $apiCPCValue);
					}
				}
			}
			########
			
			########
			// Start passing CPC's as 65% of USD Admin CPC's and 45% of GBP Admin CPC's
			// Pass CPC's for this aff_id as 65% of the admin CPC for USD bids.
			// Pass CPC's for this aff_id as 45% of the admin CPC for CAD bids.
			// This is for aff_id=ea_pushnami_api_ca
			if(($aff_id == 'ea_pushnami_api_ca') && ($siteid == 'CA')) {			
				$currency_type = '';
				$_qry_cpc_details = '{
				  "query": {
					"bool": {
					  "must": [
						{
						  "term": {
							"sponsor": "'.$jobSponsorName.'"
						  }
						},
						{
						  "term": {
							"country": "'.$siteid.'"
						  }
						}
					  ]
					}
				  }
				}';
				$result_cpc_details = postJSONCurl('http://'. ES_MAILING_CLUSTER.':9200/jos_cpc_details_new_intl/_doc/_search?size=1', $_qry_cpc_details, 'http://'. ES_MAILING_CLUSTER.':9200/jos_cpc_details_new_intl/_doc/_search?size=1');
				$result_cpc_details = json_decode($result_cpc_details, true);	
				// Fetch data
				if(count($result_cpc_details['hits']['hits']) > 0) {
					for($ilk = 0; $ilk < count($result_cpc_details['hits']['hits']); $ilk++) {
						$new_currency_type = trim($result_cpc_details['hits']['hits'][$ilk]['_source']['new_currency_type']);
						$currency_type = trim($result_cpc_details['hits']['hits'][$ilk]['_source']['currency_type']);						
					}
				}
				if($new_currency_type) {
					// Assign `CPC' based on `New Currency Type`
					switch (strtolower($new_currency_type)) {
						// Pass CPC's for this aff_id as 65% of the admin CPC for USD bids					
						case 'us':
							$apiCPCValue = round(($jobPayout*0.65),2); 
						break;
						// Pass CPC's for this aff_id as 45% of the admin CPC for CAD bids.
						case 'ca':
							$apiCPCValue = round(($jobPayout*0.45),2); 
						break;
					}
					$xml->writeElement('cpc', $apiCPCValue);
				}
				else {
					if($currency_type) {
						// Assign `CPC' based on `Currency Type`
						switch (strtolower($currency_type)) {
							// Pass CPC's for this aff_id as 65% of the admin CPC for USD bids					
							case 'us':
								$apiCPCValue = round(($jobPayout*0.65),2); 
							break;
							// Pass CPC's for this aff_id as 45% of the admin CPC for CAD bids.
							case 'ca':
								$apiCPCValue = round(($jobPayout*0.45),2); 
							break;
						}
						$xml->writeElement('cpc', $apiCPCValue);
					}
				}
			}
			########
			$aff_id_interstital = $aff_id;
			if($IS_JODDynamicInterstitialPage) {
				$aff_id_interstital = $aff_id.'_interstitial';
			}
			// DirectLink
			$jobDirectLink = "https://www.employmentalert.com/api/viewjob.php?id=".$jobListingId.$jobListingType."&siteid=".$siteid."&sub_id=".urlencode($sub_id)."&aff_id=".urlencode($aff_id_interstital)."&keyword=".bin2hex(Encode($keyword,"featured"))."&sub_category=".bin2hex(Encode($jobcat,"featured"))."&location=".bin2hex(Encode($location,"featured"))."&city=".bin2hex(Encode($city,"featured"))."&state=".bin2hex(Encode($state,"featured"))."&zip=".bin2hex(Encode($zip_code,"featured"))."&utm_source=".urlencode($affid_numeric)."&utm_medium=".urlencode($utm_medium)."&utm_campaign=".urlencode($utm_campaign)."&traffic=eaapi";
			// append `cpc` in the `job direct link`
			if($apiCPCValue) {
				$jobDirectLink .= "&cpc=".$apiCPCValue; // append `cpc` for `ea_silvertap_api_ca` in the `job direct link`
			}
			// For AU,UK,CA,DE Country 2023/03/21
			if($siteid == 'AU' || $siteid == 'UK' || $siteid == 'CA' || $siteid == 'DE') {
				// append `myid` to the `job direct link`
				if (isset($post['myid'])) { 
					$jobDirectLink .= "&myid=".$post['myid'];
				}
				// append `creative` for `creative` in the `job direct link`
				if (isset($post['creative'])) { 
					$jobDirectLink .= "&creative=".$post['creative'];
				}
				// append `creative_part` for `creative` in the `job direct link`
				if (isset($post['creative_part'])) { 
					$jobDirectLink .= "&creative_part=".$post['creative_part'];
				}
				// append `&trigger=sms` for UK SMS
				if (isset($post['trigger'])) { 
					$jobDirectLink .= "&trigger=".$post['trigger'];
				}
			}			

			$jobShortDescription = str_ireplace('{City}',ucwords($jobCity),$jobShortDescription); // replace {City} from the job description
			
			// JOD DYNAMIC LISTINGS
			// return the full description
			if(($jobSponsorReference=='JodDynamicAU') || ($jobSponsorReference=='JodDynamicUK') || ($jobSponsorReference=='JodDynamicCA')) {
				$jobShortDescription = $jobinfo['description'];
				// replace {Keyword}, {keyword} and {xkeyword} from the job title
				$jobShortDescription = str_replace(array('{Keyword}','{keyword}','{xkeyword}','(keyword)','{KEYWORD}'), $keyword, $jobShortDescription);	
			}
			
			if(strpos($aff_id, "_carousel") !== false){  
				$jobTitle = ellipsis($jobTitle, 40);
				$jobShortDescription = ellipsis($jobShortDescription, 100);
			}
			
			// append `jobposition` for Push Interstitial Affiliates
			if($IS_JODDynamicInterstitialPageQuery) {
				$jobDirectLink .= "&jbp=".$ambarCountMain;
				$jobDirectLink .= "&original_date=".$today;
				$jobDirectLink .= "&ts=".date('H:i:s');
				$jobTitle = $jobTitle." - #". rand();
				$jobCompany = $jobCompany." - #". rand();
				$jobCreatedDate = $today;
			}
	
			// Start a element
			//$xml->startElement('JOB');
			$xml->writeElement('id', $jobListingId);
			$xml->writeElement('title', $jobTitle);
			$xml->writeElement('company', $jobCompany);
			$xml->writeElement('description', $jobShortDescription);
			$xml->writeElement('location', $jobCity);
			$xml->writeElement('state', $jobState);
			$xml->writeElement('country', $jobCountry);	
			$xml->writeElement('category', $categoryName);
			$xml->writeElement('categoryId', $jobCategoryID);
			$xml->writeElement('created_date', $jobCreatedDate);
			$xml->writeElement('url', $jobDirectLink);
			// For Personal use
			if ((isset($post['spninfo'])=="yes") || ($ip_address=='65.0.157.221') || (isset($post['JODad_'])=="JODad")) {
				$xml->writeElement('name', $jobSponsorName);
				$xml->writeElement('reference', $jobSponsorReference);
				$xml->writeElement('currency_type', $currency_type);
				$xml->writeElement('payout', $jobPayout);
				$xml->writeElement('rank', $jobRanking);
				$xml->writeElement('redis', $_aero);
				$xml->writeElement('match', $value);
				$xml->writeElement('jobposition', $ambarCountMain);
				$xml->writeElement('jobptype', $jobListingType);		
			}
			$xml->endElement();   /* </JOB> */				
	}	
	
				
?>