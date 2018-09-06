<?php
// USE THIS THE THE VIEW FILE TO ACTIVE A SPECIFIC MENU
//$this->assign('active', '/db/staff/jobs/client_jobs');
if(isset($this->request->params['prefix']) && ($this->request->params['prefix'] == "staff")) {
    if (is_numeric($this->request->session()->read('Auth.staff.id'))) {

        $menuOptions = array(
            "Customers" => $this->Url->build(array('controller' => 'Customers', 'action' => 'index', '?' => ['type' => 'customers'])),
            "Suppliers" => $this->Url->build(array('controller' => 'Customers', 'action' => 'index', '?' => ['type' => 'suppliers'])),
            "Contacts" => $this->Url->build(array('controller' => 'Contacts', 'action' => 'index')),
            "Jobs" => [
                'List'=> $this->Url->build(array('controller' => 'Jobs', 'action' => 'index')),
                'Open Jobs'=> $this->Url->build(array('controller' => 'Jobs', 'action' => 'open_jobs')),
                'Time on jobs'=> $this->Url->build(array('controller' => 'Jobs', 'action' => 'time_on_jobs')),
                'Client Jobs'=> $this->Url->build(array('controller' => 'Jobs', 'action' => 'client_jobs')),  //Routing - use report action
            ],
            "Worksheets" => $this->Url->build(array('controller' => 'Works', 'action' => 'summary')),
            "Purchase Orders" => $this->Url->build(array('controller' => 'PurchaseOrders', 'action' => 'index')),
            "Staff" => [
                'List' => $this->Url->build(array('controller' => 'Staffs', 'action' => 'index')),
                'Productivity' => $this->Url->build(array('controller' => 'Staffs', 'action' => 'productivity')),
            ],
            "Quotes" => [
                "List" => $this->Url->build(array('controller' => 'Quotes', 'action' => 'index')),
                "Report" => $this->Url->build(array('controller' => 'Quotes', 'action' => 'report')),
                "Detail Types" => $this->Url->build(array('controller' => 'QuoteDetailTypes', 'action' => 'index')),
                "Groups" => $this->Url->build(array('controller' => 'QuoteGroups', 'action' => 'index')),
                "Sub Groups" => $this->Url->build(array('controller' => 'QuoteSubGroups', 'action' => 'index')),
            ],
            "Lookups" => [
                "Customer Types" => $this->Url->build(array('controller' => 'CustomerTypes', 'action' => 'index')),
                "Job Categories" => $this->Url->build(array('controller' => 'JobCategories', 'action' => 'index')),
                "Job Statuses" => $this->Url->build(array('controller' => 'JobStatuses', 'action' => 'index')),
                "Regions" => $this->Url->build(array('controller' => 'Regions', 'action' => 'index')),
                "Staff Access" => $this->Url->build(array('controller' => 'Securities', 'action' => 'index')),
                "Staff Categories" => $this->Url->build(array('controller' => 'StaffCategories', 'action' => 'index')),
                "Staff Rates" => $this->Url->build(array('controller' => 'StaffRates', 'action' => 'index')),
                "Work Groups" => $this->Url->build(array('controller' => 'WorkGroups', 'action' => 'index')),
                "Work Types" => $this->Url->build(array('controller' => 'WorkTypes', 'action' => 'index')),
                "Work Type Groups" => $this->Url->build(array('controller' => 'WorkTypeGroups', 'action' => 'index'))
            ],
            "Logout" => $this->Url->build(array('controller' => 'Staffs', 'action' => 'logout'))
        );
    }
}


$prefix=$this->request->prefix;
$action = ($this->request['action'] == 'index') ? "" : "/".$this->request['action'];
$active = '';
if($this->fetch('active'))
    $active = $this->fetch('active');

if (!function_exists("drawSubMenu")) {
    function drawSubMenu($options = array(), $prefix, $action, $active = NULL) {
        $submenu = "<ul>\n";
        $parentOpen = 0;
        foreach ($options as $optionName => $optionLink) {
            $class = "";
            $subsubmenu = "";
            if (is_array($optionLink)) {
                list($subParentOpen, $subsubmenu) = drawSubMenu($optionLink,$prefix,$action, $active);
                if ($subParentOpen == 1) {
                    $submenu .= "<li class=\"k-state-active\"><span class=\"k-link k-header k-state-selected\">" . $optionName . "</span>";
                    $parentOpen = 1;
                } else {
                    $submenu .= "<li>" . $optionName;
                }
                $submenu .= $subsubmenu;
                $submenu .= "</li>\n";
            } else {
                $match = "#^" . $optionLink . "#";
                $path = explode("/", $_SERVER['REQUEST_URI']);
                if (isset($path[3])) {
                    $fullpath =  "/db/".$prefix."/" . $path[3]."".$action;
                } else {
                    $fullpath = "";
                }
                if ($active == $optionLink || $fullpath == $optionLink) {
                    $class = "k-link k-state-selected k-panel-menu-selected";
                    $parentOpen = 1;
                }
                $submenu .= "<li class=\"" . $class . "\">";
                $submenu .= "<a href=\"" . $optionLink . "\">" . $optionName . "</a>";
                $submenu .= "</li>\n";
            }
        }
        $submenu .= "</ul>\n";
        return array($parentOpen, $submenu);
    }
}
$menu = "<ul id=\"menubar\">\n";
foreach ($menuOptions as $level => $options) {
    $parentOpen = 0;
    $submenu = "";
    $selectedpage = false;
    if (is_array($options)) {
        list($parentOpen, $submenu) = drawSubMenu($options, $prefix, $action, $active);
    } else {
        $path = explode("/", $_SERVER['REQUEST_URI']);
        if (isset($path[3])) {
            $fullpath =  "/db/".$prefix."/" . $path[3]."".$action;
        } else {
            $fullpath = "";
        }
        if($active == $options || $fullpath == $options) {
            $selectedpage = true;
        }
        $level = "<a href=\"" . $options . "\">" . $level . "</a>";
    }
    $class = "";
    if ($parentOpen == 1) {
        $menu .= "<li class=\"k-state-active\"><span class=\"k-link k-header k-state-selected\">" . $level . "</span>";
    } else {
        if($selectedpage == true) {
            $menu .= "<li class=\"k-state-active\">" . $level;
        } else {
            $menu .= "<li>" . $level;
        }
    }
    $menu .= $submenu;
    $menu .= "</li>\n";
}
$menu .= "</ul>\n";

echo $menu;
?>

<script>
    $(document).ready(function () {
        $("#menubar").kendoPanelBar({
            expandMode: "multiple"
        });
    });
</script>