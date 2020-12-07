<?php

    if ( ! function_exists('custom_form_dropdown'))

    {

        function custom_form_dropdown($name = '', $options = array(), $selected = array(), $extra = ''){

            if ( ! is_array($selected))

		{

			$selected = array($selected);

		}

                

		// If no selected state was submitted we will attempt to set it automatically

		if (count($selected) === 0)

		{

			// If the form name appears in the $_POST array we have a winner!

			if (isset($_POST[$name]))

			{

				$selected = array($_POST[$name]);

			}

		}



		if ($extra != '') $extra = ' '.$extra;



		$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';



		$form = '<select name="'.$name.'"'.$extra.$multiple.">";



		foreach ($options as $val)

		{

                    $sel = (in_array($val, $selected)) ? ' selected="selected"' : '';

                    $form .= '<option value="'.$val.'"'.$sel.'>'.(string) $val."</option>\n";

			

		}



		$form .= '</select>';



		return $form;

        }

    }

?>