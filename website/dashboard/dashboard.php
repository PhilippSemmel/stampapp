<?php
require_once "../../stempelAppManager.php";
if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

abstract class DashboardPage
{
    function print_html()
    { ?>
        <!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <?php $this->print_html_head(); ?>
        </head>
        <body>
        <?php $this->print_html_body(); ?>
        </body>
        </html>
    <?php }

    function print_html_head()
    { ?>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../../css/dashboard.css">
        <link rel="stylesheet" href="../../css/header.css">
        <link rel="stylesheet" href="../../css/footer.css">
        <link rel="stylesheet" href="../../css/config.css">
        <title></title>
    <?php }

    function print_html_body()
    { ?>
        <?php include '../../header.inc.php'; ?>
        <?php include 'buttonlist.inc.php'; ?>
        <?php $this->print_body_content(); ?>
        <?php include '../../footer.inc.php'; ?>
    <?php }

    abstract public function print_body_content();
}

class EntityTable extends DashboardPage
{
    private $entities;
    private $file_name;
    private $pageNumber;
    private $totalPages;

    function __construct($entities, $file_name, $pageNumber, $totalPages)
    {
        $this->entities = $entities;
        $this->file_name = $file_name;
        $this->pageNumber = $pageNumber;
        $this->totalPages = $totalPages;
    }

    function print_body_content()
    {
        if (count($this->entities) > 0) { ?>
            <div class="container flex">
                <table>
                    <tr>
                        <?php printColumnNames() ?>
                    </tr>
                    <?php foreach ($this->entities as $entity) {
                        printEntityRow($entity);
                    } ?>
                    <?php $this->print_page_links() ?>
                </table>
            </div>
        <?php }
    }

    function print_page_links()
    {
        if ($this->totalPages != 1) { ?>
            <td>
                <?php for ($i = 1; $i <= $this->totalPages; $i++) {
                    echo ($i != $this->pageNumber) ? "<a href='" . $this->file_name . "?id=" . $_GET['id'] . "&page=$i'>$i</a>" : "$this->pageNumber";
                } ?>
            </td>
        <?php } ?>
    <?php }
}

class EntityPage extends DashboardPage
{
    function print_body_content()
    { ?>
        <div class="container flex">
            <table>
                <?php print_entity_values() ?>
            </table>
        </div>
    <?php }
}
