<?php
/**
 * Created by PhpStorm.
 * User: wangning
 * Date: 2018/6/9
 * Time: 上午3:00
 */

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

    try  {
        $connection = new PDO($dsn, $username, $password, $options);
        $connection -> exec('set names utf8');
        $sql = "SELECT * FROM NationalCompetitionGrade
            WHERE teamMember1 = :location or teamMember2 = :location or teamMember3 = :location or teacher = :location";

        $location = $_POST['teamMember'];
        $statement = $connection->prepare($sql);
        $statement->bindParam(':location', $location, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<?php require "templates/header.php"; ?>

<?php
if (isset($_POST['submit'])) {
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>Results</h2>

        <table>
            <thead>
            <tr>
                <th>序号</th>
                <th>年份</th>
                <th>队长</th>
                <th>队员2</th>
                <th>队员3</th>
                <th>指导老师</th>
                <th>校区</th>
                <th>区级奖项</th>
                <th>参赛组别</th>
                <th>国家级奖项</th>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?php echo escape($row["serialNumber"]); ?></td>
                    <td><?php echo escape($row["years"]); ?></td>
                    <td><?php echo escape($row["teamMember1"]); ?></td>
                    <td><?php echo escape($row["teamMember2"]); ?></td>
                    <td><?php echo escape($row["teamMember3"]); ?></td>
                    <td><?php echo escape($row["teacher"]); ?></td>
                    <td><?php echo escape($row["campus"]); ?> </td>
                    <td><?php echo escape($row["awardLevel"]); ?> </td>
                    <td><?php echo escape($row["groups"]); ?> </td>
                    <td><?php echo escape($row["nationPrize"]);?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php } else { ?>
        <blockquote>No results found for <?php echo escape($_POST['location']); ?>.</blockquote>
    <?php }
} ?>

    <h2>Find user based on location</h2>

    <form method="post">
        <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
        <label for="location">Location</label>
        <input type="text" id="location" name="location">
        <input type="submit" name="submit" value="View Results">
    </form>

    <a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>