<?php

namespace puresoft\easylang;
use Exception;

/**
 *
 * Easy Lang PHP Class.
 *
 * By using this class you can use multiple languages in PHP pages.
 * Languages are saved in a UTF-8 .ini file and will load by their language short names.
 * This class provided with Getters and Setters, so you can easily customize it.
 *
 * Usage:
 *
 * ```php
 * $languages_path = 'languages/'; // Don't forget '/' at the end of path
 * $language_short_name = 'en';
 * $is_rtl = false; // This used for founding language direction
 * $translate = new EasyLang( $languages_path, $language_short_name, $is_rtl );
 * ```
 *
 * @author Hesam Gholami <hesamgholami@yahoo.com>
 */
class EasyLang
{
	protected $lang = '';
	protected $lang_path = '';
	protected $translates = [];
	protected $is_rtl = false;

	/**
	 * Constructs new instance of EasyLang object with given language details.
	 *
	 * @param string $languages_path
	 * @param string $language_short_name
	 * @param bool $is_rtl
	 * @throws Exception
	 */
	function __construct($languages_path, $language_short_name, $is_rtl = false)
	{
		$this->refreshTranslates($languages_path, $language_short_name, $is_rtl);
	}

	/**
	 * Refresh text of translates and also could be used to change the language
	 *
	 * @param string $languages_path
	 * @param string $language_short_name *
	 * @param bool $is_rtl
	 * @throws Exception
	 */
	public function refreshTranslates($languages_path, $language_short_name, $is_rtl = false)
	{
		$this->setLang($language_short_name);
		$this->setLangPath($languages_path);
		$this->setIsRtl($is_rtl);

		// Make path of translate file: PATH + [LANGUAGE SHORT NAME] + .ini
		$file_path = $this->getLangPath() . $this->getLang() . '.ini';

		if (file_exists($file_path)) {
			// If file exists, use it
			$this->setAllTranslates(parse_ini_file($file_path));
		} else {
			// Otherwise throw an exception to indicate that file is missing
			throw new Exception("Language file '{$file_path}' does not exist.\n");
		}
	}

	/**
	 * Sets current language direction.
	 *
	 * @param boolean $is_rtl
	 */
	protected function setIsRtl($is_rtl)
	{
		$this->is_rtl = $is_rtl;
	}

	/**
	 * Returns current language directory path.
	 *
	 * @return string
	 */
	public function getLangPath()
	{
		return $this->lang_path;
	}

	/**
	 * Changes languages directory path.
	 *
	 * @param string $lang_path
	 */
	protected function setLangPath($lang_path)
	{
		$this->lang_path = $lang_path;
	}

	/**
	 * Returns current language short name.
	 *
	 * @return string
	 */
	public function getLang()
	{
		return $this->lang;
	}

	/**
	 * Changes current language short name. This should be same as name of .ini file which is on the disk.
	 *
	 * @param string $lang
	 */
	protected function setLang($lang)
	{
		$this->lang = $lang;
	}

	/**
	 * Sets current language text.
	 *
	 * @param array $translates
	 */
	protected function setAllTranslates($translates)
	{
		$this->translates = $translates;
	}

	/**
	 * Changes the current language.
	 *
	 * @param string $language_short_name
	 */
	public function changeLanguage($language_short_name)
	{
		$this->refreshTranslates($this->getLangPath(), $language_short_name);
	}

	/**
	 * Gets a constant and return its text from translates. If it fails to find the constant it will return constant name itself with double start surrounded.
	 *
	 * @param string $constant
	 * @return string
	 */
	public function getTranslate($constant)
	{
		$tmp_trans = $this->getAllTranslates();

		return isset($tmp_trans[$constant]) ? $tmp_trans[$constant] : '**' . $constant . '**';
	}

	/**
	 * Returns current translates array.
	 *
	 * @return array
	 */
	public function getAllTranslates()
	{
		return $this->translates;
	}

	/**
	 * Whether the current using language direction is right to left or not.
	 *
	 * @return boolean
	 */
	public function isRtl()
	{
		return $this->is_rtl;
	}
}
