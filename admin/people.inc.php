<?php

function get_persons_details($person_id)
{
    global $link;

    $sql    = "SELECT * FROM people WHERE id=\"".$person_id."\" LIMIT 1";
    $result = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

    if ($result)
    {
        while ($row = mysql_fetch_array($result))
        {
            return $row;
        }
    }
}

function create_person($Ad_number, $FirstName, $LastName, $reg_group_id, $photo_data)
{
    global $link;

    $sql    = 'INSERT INTO people (id, first_name, last_name, reg_group, in_out, photo) VALUES ("'.$Ad_number.'", "'
        .$FirstName.'", "'
        .$LastName.'", '
        .$reg_group_id.', "out", "'
        .$photo_data.'");';
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error() . ' on line ' . __LINE__);

    if ($result)
    {
        echo '<div class="success">Added '.$FirstName.' '.$LastName.'</div>';
    }
    else
    {
        echo '<div class="error">Error adding person.</div>';
    }
}

function update_person($old_ad_no, $new_ad_no, $FirstName, $LastName, $reg_group_id, $in_out = "out", $photo_data = Null)
{
    global $link;

    $sql    = 'UPDATE people SET id="'.(string)$new_ad_no
        .'", first_name="'.$FirstName
        .'", last_name="'.$LastName
        .'", reg_group="'.$reg_group_id
        .'", in_out="'.$in_out.'"'
        .', photo="'.$photo_data
        .'" WHERE id="'.$old_ad_no.'";';
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error()." on line ".__LINE__);

    if ($result)
    {
        echo '<div class="success">Updated '.$FirstName.' '.$LastName.'</div>';
    }
    else
    {
        echo '<div class="error">Error updating person.</div>';
    }
}


function delete_person($person_id)
{
    global $link;
    
    $sql    = "DELETE FROM people WHERE id=\"".$person_id."\" LIMIT 1";
    return mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());
}

/*** Check if person exists, and create if it does not ***/
function check_and_create_person($Ad_number, $FirstName, $LastName, $reg_group_id, $photo_data)
{
    global $link;

    $sql = "SELECT * FROM people WHERE id=\"".$Ad_number."\" LIMIT 1";
    $res = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

    if (mysql_num_rows($res) == 0)
    {
        create_person($Ad_number, $FirstName, $LastName, $reg_group_id, $photo_data);
    }
    else if (mysql_num_rows($res) == 1)
    {
        create_person($Ad_number, $Ad_number, $FirstName, $LastName, $reg_group_id, "out", $photo_data);
    }
}

?>

