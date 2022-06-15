<?php
if (!isset($_SESSION["name"])) {
    header("Location: ../login/login.php");
    exit;
}

class EntityTable
{
    private $entities;
    private $file_name;
    private $page;
    private $totalPages;

    function __construct($entities, $file_name, $page, $totalPages)
    {
        $this->entities = $entities;
        $this->file_name = $file_name;
        $this->page = $page;
        $this->totalPages = $totalPages;
    }

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
        <div class="container flex">
            <table>
                <tr>
                    <?php printColumnNames() ?>
                </tr>
                <?php foreach ($this->entities as $entity) {
                    printEntityRow($entity);
                } ?>
                <td><?php $this->print_page_links() ?></td>
            </table>
        </div>
        <?php include '../../footer.inc.php'; ?>
    <?php }

    function print_page_links()
    {
        for ($i = 1; $i <= $this->totalPages; $i++) {
            echo ($i != $this->page) ? "<a href='" . $this->file_name . "?id=" . $_GET['id'] . "&page=$i'>$i</a>" : "<u>$this->page</u>";
        }
    }
}
