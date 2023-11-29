<?php

namespace Sprint\Migration;


class AddHighloadBlockBookRatings20231129045405 extends Version
{
	protected $description = "Добавление Хайлоадблока BookRatings";

	protected $moduleVersion = "4.6.1";

	/**
	 * @return bool|void
	 * @throws Exceptions\HelperException
	 */
	public function up()
	{
		$helper = $this->getHelperManager();
		$hlblockId = $helper->Hlblock()->saveHlblock(array(
			'NAME' => 'BookRatings',
			'TABLE_NAME' => 'book_ratings',
			'LANG' =>
				array(
					'ru' =>
						array(
							'NAME' => 'Оценки книг',
						),
					'en' =>
						array(
							'NAME' => 'Book Ratings',
						),
				),
		));
		$helper->Hlblock()->saveField($hlblockId, array(
			'FIELD_NAME' => 'UF_BOOK_ID',
			'USER_TYPE_ID' => 'hlblock',
			'XML_ID' => 'UF_BOOK_ID',
			'SORT' => '100',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'Y',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
			'SETTINGS' =>
				array(
					'DISPLAY' => 'LIST',
					'LIST_HEIGHT' => 1,
					'HLBLOCK_ID' => 'Books',
					'HLFIELD_ID' => 0,
					'DEFAULT_VALUE' => 0,
				),
			'EDIT_FORM_LABEL' =>
				array(
					'en' => 'Book ID',
					'ru' => 'Идентификатор книги',
				),
			'LIST_COLUMN_LABEL' =>
				array(
					'en' => 'Book ID',
					'ru' => 'Идентификатор книги',
				),
			'LIST_FILTER_LABEL' =>
				array(
					'en' => 'Book ID',
					'ru' => 'Идентификатор книги',
				),
			'ERROR_MESSAGE' =>
				array(
					'en' => '',
					'ru' => '',
				),
			'HELP_MESSAGE' =>
				array(
					'en' => '',
					'ru' => '',
				),
		));
		$helper->Hlblock()->saveField($hlblockId, array(
			'FIELD_NAME' => 'UF_USER_ID',
			'USER_TYPE_ID' => 'integer',
			'XML_ID' => 'UF_USER_ID',
			'SORT' => '100',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'Y',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
			'SETTINGS' =>
				array(
					'SIZE' => 20,
					'MIN_VALUE' => 0,
					'MAX_VALUE' => 0,
					'DEFAULT_VALUE' => NULL,
				),
			'EDIT_FORM_LABEL' =>
				array(
					'en' => 'User ID',
					'ru' => 'Идентификатор пользователя',
				),
			'LIST_COLUMN_LABEL' =>
				array(
					'en' => 'User ID',
					'ru' => 'Идентификатор пользователя',
				),
			'LIST_FILTER_LABEL' =>
				array(
					'en' => 'User ID',
					'ru' => 'Идентификатор пользователя',
				),
			'ERROR_MESSAGE' =>
				array(
					'en' => '',
					'ru' => '',
				),
			'HELP_MESSAGE' =>
				array(
					'en' => '',
					'ru' => '',
				),
		));
		$helper->Hlblock()->saveField($hlblockId, array(
			'FIELD_NAME' => 'UF_MARK',
			'USER_TYPE_ID' => 'enumeration',
			'XML_ID' => 'UF_MARK',
			'SORT' => '100',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
			'SETTINGS' =>
				array(
					'DISPLAY' => 'CHECKBOX',
					'LIST_HEIGHT' => 5,
					'CAPTION_NO_VALUE' => '',
					'SHOW_NO_VALUE' => 'Y',
				),
			'EDIT_FORM_LABEL' =>
				array(
					'en' => 'Mark',
					'ru' => 'Оценка',
				),
			'LIST_COLUMN_LABEL' =>
				array(
					'en' => 'Mark',
					'ru' => 'Оценка',
				),
			'LIST_FILTER_LABEL' =>
				array(
					'en' => 'Mark',
					'ru' => 'Оценка',
				),
			'ERROR_MESSAGE' =>
				array(
					'en' => '',
					'ru' => '',
				),
			'HELP_MESSAGE' =>
				array(
					'en' => '',
					'ru' => '',
				),
			'ENUM_VALUES' =>
				array(
					0 =>
						array(
							'VALUE' => '1',
							'DEF' => 'N',
							'SORT' => '500',
							'XML_ID' => '1',
						),
					1 =>
						array(
							'VALUE' => '2',
							'DEF' => 'N',
							'SORT' => '500',
							'XML_ID' => '2',
						),
					2 =>
						array(
							'VALUE' => '3',
							'DEF' => 'N',
							'SORT' => '500',
							'XML_ID' => '3',
						),
					3 =>
						array(
							'VALUE' => '4',
							'DEF' => 'N',
							'SORT' => '500',
							'XML_ID' => '4',
						),
					4 =>
						array(
							'VALUE' => '5',
							'DEF' => 'N',
							'SORT' => '500',
							'XML_ID' => '5',
						),
				),
		));
	}

	public function down()
	{
		//your code ...
	}
}
