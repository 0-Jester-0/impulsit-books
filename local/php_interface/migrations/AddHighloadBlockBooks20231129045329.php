<?php

namespace Sprint\Migration;


class AddHighloadBlockBooks20231129045329 extends Version
{
	protected $description = "Добавление Хайлоадблока Books";

	protected $moduleVersion = "4.6.1";

	/**
	 * @return bool|void
	 * @throws Exceptions\HelperException
	 */
	public function up()
	{
		$helper = $this->getHelperManager();
		$hlblockId = $helper->Hlblock()->saveHlblock(array(
			'NAME' => 'Books',
			'TABLE_NAME' => 'books',
			'LANG' =>
				array(
					'ru' =>
						array(
							'NAME' => 'Книги',
						),
					'en' =>
						array(
							'NAME' => 'Books',
						),
				),
		));
		$helper->Hlblock()->saveField($hlblockId, array(
			'FIELD_NAME' => 'UF_NAME',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_NAME',
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
					'ROWS' => 1,
					'REGEXP' => '',
					'MIN_LENGTH' => 0,
					'MAX_LENGTH' => 0,
					'DEFAULT_VALUE' => '',
				),
			'EDIT_FORM_LABEL' =>
				array(
					'en' => 'Name',
					'ru' => 'Название',
				),
			'LIST_COLUMN_LABEL' =>
				array(
					'en' => 'Name',
					'ru' => 'Название',
				),
			'LIST_FILTER_LABEL' =>
				array(
					'en' => 'Name',
					'ru' => 'Название',
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
			'FIELD_NAME' => 'UF_AUTHOR',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_AUTHOR',
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
					'ROWS' => 1,
					'REGEXP' => '',
					'MIN_LENGTH' => 0,
					'MAX_LENGTH' => 0,
					'DEFAULT_VALUE' => '',
				),
			'EDIT_FORM_LABEL' =>
				array(
					'en' => 'Author',
					'ru' => 'Автор',
				),
			'LIST_COLUMN_LABEL' =>
				array(
					'en' => 'Author',
					'ru' => 'Автор',
				),
			'LIST_FILTER_LABEL' =>
				array(
					'en' => 'Author',
					'ru' => 'Автор',
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
			'FIELD_NAME' => 'UF_PICTURE',
			'USER_TYPE_ID' => 'file',
			'XML_ID' => 'UF_PICTURE',
			'SORT' => '100',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
			'SETTINGS' =>
				array(
					'SIZE' => 20,
					'LIST_WIDTH' => 0,
					'LIST_HEIGHT' => 0,
					'MAX_SHOW_SIZE' => 0,
					'MAX_ALLOWED_SIZE' => 0,
					'EXTENSIONS' =>
						array(
							'jpg' => true,
							'png' => true,
							'jpeg' => true,
						),
					'TARGET_BLANK' => 'Y',
				),
			'EDIT_FORM_LABEL' =>
				array(
					'en' => 'Picture',
					'ru' => 'Изображение',
				),
			'LIST_COLUMN_LABEL' =>
				array(
					'en' => 'Picture',
					'ru' => 'Изображение',
				),
			'LIST_FILTER_LABEL' =>
				array(
					'en' => 'Picture',
					'ru' => 'Изображение',
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
			'FIELD_NAME' => 'UF_RATING',
			'USER_TYPE_ID' => 'double',
			'XML_ID' => 'UF_RATING',
			'SORT' => '100',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
			'SETTINGS' =>
				array(
					'PRECISION' => 2,
					'SIZE' => 5,
					'MIN_VALUE' => 0.0,
					'MAX_VALUE' => 5.0,
					'DEFAULT_VALUE' => 0.0,
				),
			'EDIT_FORM_LABEL' =>
				array(
					'en' => 'Rating',
					'ru' => 'Рейтинг',
				),
			'LIST_COLUMN_LABEL' =>
				array(
					'en' => 'Rating',
					'ru' => 'Рейтинг',
				),
			'LIST_FILTER_LABEL' =>
				array(
					'en' => 'Rating',
					'ru' => 'Рейтинг',
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
	}

	public function down()
	{
		//your code ...
	}
}
