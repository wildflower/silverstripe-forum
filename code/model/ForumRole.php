<?php

/**
 * ForumRole is a decorator for the Member class,
 * designed to add extra forum-specific fields
 * to the {@link Member}, such as Username,
 * and Avatar.
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class ForumRole extends DataObjectDecorator {

	/**
	 * Return a list of countries, along with
	 * their corresponding codes, suitable for
	 * populating a {@link DropdownField}.
	 *
	 * @var array
	 */
	public $countries = array(
		'AF' => 'Afghanistan',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia and Herzegowina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'BN' => 'Brunei Darussalam',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Islands',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CG' => 'Congo',
		'CD' => 'Congo, The Democratic Republic of The',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Cote d\'Ivoire',
		'HR' => 'Croatia (Hrvatska)',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'TP' => 'East Timor',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FK' => 'Falkland Islands (Malvinas)',
		'FO' => 'Faroe Islands',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'FX' => 'France, Metropolitan',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard and Mc Donald Islands',
		'VA' => 'Holy See (Vatican City State)',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran (Islamic Republic Of)',
		'IQ' => 'Iraq',
		'IE' => 'Ireland',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KP' => 'Korea, Democratic People\'s Republic Of',
		'KR' => 'Korea, Republic Of',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Lao People\'s Democratic Republic',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libyan Arab Jamahiriya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macau',
		'MK' => 'Macedonia, Former Yugoslav Republic Of',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia, Federated States Of',
		'MD' => 'Moldova, Republic Of',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'AN' => 'Netherlands Antilles',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'KN' => 'Saint Kitts and Nevis',
		'LC' => 'Saint Lucia',
		'VC' => 'Saint Vincent and the Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome and Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia (Slovak Republic)',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia, South Sandwich Islands',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SH' => 'St. Helena',
		'PM' => 'St. Pierre and Miquelon',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SJ' => 'Svalbard and Jan Mayen Islands',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syrian Arab Republic',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania, United Republic Of',
		'TH' => 'Thailand',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UM' => 'United States Minor Outlying Islands',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VE' => 'Venezuela',
		'VN' => 'Viet Nam',
		'VG' => 'Virgin Islands (British)',
		'VI' => 'Virgin Islands (U.S.)',
		'WF' => 'Wallis and Futuna Islands',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'YU' => 'Yugoslavia',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);
	
	/**
	 * Augment the Member DB fields with additional
	 * fields specific to the forum.
	 *
	 * @return array
	 */
	function extraStatics() {
		return array(
			'db' => array(
				'FirstName' => 'Varchar(50)',
				'Surname' => 'Varchar(50)',
				'Email' => 'Varchar(50)',
				'Country' => 'Varchar(50)',
				'Username' => 'Varchar(50)',
				'Description' => 'Text',
				'CanViewName' => 'Boolean'
			),
			'has_one' => array(
				'Avatar' => 'Image',
				'Icon' => 'Image',
				'Rank' => 'Rank'
			),
			'has_many' => array(
				'Threads' => 'Thread',
				'Posts' => 'Post'
			),
			'many_many' => array(
				'ModeratedForums' => 'ForumPage'
			)
		);
	}
	
	/**
	 * Is the forum ranking system enabled?
	 *
	 * @return boolean
	 */
	function IsRankingEnabled() {
		if(Rank::$enabled) return true;
		else return false;
	}

	/**
	 * Return the full country name by looking up
	 * the current user's country code which is set
	 * as a country code. e.g "NZ" would resolve to
	 * "New Zealand".
	 * 
	 * @return mixed string|null
	 */	
	function CountryLookup() {
		$countryCode = $this->owner->Country;
		
		if(isset($this->countries[$countryCode]))	{
			return $this->countries[$countryCode];
		} else {
			return null;
		}
	}
	
	/**
	 * Resizing is done if the original avatar image is greater
	 * than 80 pixels in width. If the image is exactly 80 pixels
	 * in width, or less, no resizing is done and the original image
	 * URL is returned.
	 *
	 * To override the 80 pixels default, just set the $width
	 * parameter to a different number. This can be done in the
	 * templates like this (inside a Member control block scope):
	 * 
	 * <code>
	 * $AvatarURLByWidth(100)
	 * </code>
	 *
	 * @todo check the height of the source image as well as the
	 * width before making a determination on whether it should
	 * be resized or not.
	 * 
	 * @todo allow easy customisation of these width/height values.
	 * Perhaps allow setting them in the _config.php file?
	 *
	 * @return mixed string of image URL | false if no image
	 */
	function AvatarURLByWidth($width = 80) {
		$image = $this->owner->Avatar();
		
		if($image && $image->ID) {
			if($image->width > $width) {
				$thumb = $image->SetWidth($width);
				return $thumb->URL();
			} else {
				return $image->URL();
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Form fields suitable for editing an instance
	 * of {@link Member} on a standard form, specific
	 * to the forum.
	 *
	 * @return FieldSet
	 */
	function forumFields() {
		$fields = new FieldSet(
			new CompositeField(
				new TextField('FirstName', 'First name'),
				new TextField('Surname', 'Surname'),
				new TextareaField('Description', 'A short description about yourself'),
				new CheckboxField(
					'CanViewName',
					'I would like my real name to be viewable by anyone.'
				),
				new TextField('Username', 'Username'),
				new EmailField('Email', 'Email address'),
				new DropdownField(
					'Country',
					'Country',
					$this->countries,
					'',
					null,
					'(Select one)'
				)
			),
			new CompositeField(
				new SimpleImageField('Avatar', 'Upload an avatar'),
				new SimpleImageField('Icon', 'Upload an icon')
			)
		);
		
		$this->owner->extend('augmentForumFields', $fields);
		
		return $fields;
	}
	
	/**
	 * Return a list of Member fields, defined on this role
	 * that are required to be filled out on a standard form
	 * specific to the forum. Used in conjunction with
	 * {@link ForumRole->forumFields()}.
	 * 
	 * @return RequiredFields
	 */
	function requiredForumFields() {
		$fields = new RequiredFields(
			'FirstName',
			'Surname',
			'Username',
			'Email'
		);
		
		$this->owner->extend('augmentRequiredForumFields', $fields);
		
		return $fields;
	}
	
}

?>