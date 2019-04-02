<input type="text" placeholder="验证码" name="verifycode" class="captcha"><br><br>
<img id="captcha_img" src="captcha.php?r=<?php echo rand();?>" alt="验证码">
<label><a href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='captcha.php?r='+Math.random()">换一个</a> </label>