<?php

/*** create a reg group.*/
function create_reg_group($reg_group_name, $reg_group_bg_colour = 'ffffff', $reg_group_text_colour = '000000')
{
    global $link;
    
    $sql    = "INSERT INTO reg_groups (id, name, background_colour, text_colour) VALUES (NULL, \"".$reg_group_name."\", \"".$reg_group_bg_colour."\", \"".$reg_group_text_colour."\");";
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

    if ($result)
    {
        echo '<div class="success">Added reg group '.$reg_group_name.'</div>';
    }
    else
    {
        echo '<div class="error:>Error adding a reg group.</div>';
    }
}

/*** check if reg group exists, and create if it does not. ***/
function check_and_create_reg_group($reg_group_name)
{
    global $link;

    $sql = "SELECT * FROM reg_groups WHERE name=\"".$reg_group_name."\" LIMIT 1";
    $res = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

    if (mysql_num_rows($res) == 0)
    {
        create_reg_group($reg_group_name);
    }
}


/*** lookup reg_group_id. ***/
function lookup_reg_group_id($reg_group_name)
{
    global $link;

    $sql = "SELECT * FROM reg_groups WHERE name=\"".$reg_group_name."\" LIMIT 1";
    $res = mysql_query($sql, $link) or die('Invalid query ['.$sql.']: ' . mysql_error() . " on line ". __LINE__);

    if ($res)
    {
        $row_rg = mysql_fetch_array($res);

        return $row_rg["id"];
    }
}

/*** move all people from a reg group to the unallocated group. */

function unallocate_from_reg_group($reg_group_id)
{
    global $link;
    
// move all users in reg group to unalocated group (id #0)  
    $sql    = "UPDATE people SET reg_group=0 WHERE reg_group=".$id;
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

    if ($result)
    {
        echo '<div class="success">Unallocated all users in reg group</div>';
    }
}


/*** delete reg group. */
function remove_reg_group($id)
{
    global $link;
    
// remove group from reg_group table
    $sql    = "DELETE FROM reg_groups WHERE id=".$id." LIMIT 1";
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

    if ($result)
    {
        echo '<div class="success">Removed reg group</div>';
    }
}

/*** get the name of a reg group. */
function get_reg_group_name($id)
{
    global $link;

    $sql    = "SELECT * FROM reg_groups WHERE id=".$id." LIMIT 1";
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

    if ($result)
    {
        while ($row = mysql_fetch_array($result))
        {
            return $row["name"];
        }
    }
    return "";
}


/*** get data for a reg group. */
function get_reg_group($id)
{
    global $link;

    $sql    = "SELECT * FROM reg_groups WHERE id=".$id." LIMIT 1";
    $result = mysql_query($sql, $link) or die('Invalid query: ' . mysql_error());

    if ($result)
    {
        while ($row = mysql_fetch_array($result))
        {
            return $row;
        }
    }
    return "";
}



?>

