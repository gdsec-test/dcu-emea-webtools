<p>
<form id="1" action="index.php" method="post">
    <label for="url">URL</label>
    <textarea id="url" name="url" rows="10" cols="50"><?php if ($_GET["action"] === "recheck" && isset($_GET["url"])) { echo base64_decode($_GET["url"]); } ?></textarea></p>
    <p><label for="useragent">Multiple User Agents: </label>
    <input type="checkbox" name="useragent" value="mobile"></p>
    <p><input type="submit" name="analyze" value="analyze" /></p>
</form>
</p>
