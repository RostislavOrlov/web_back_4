<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<title>BackEnd4</title>
</head>

<body>
	<div class="form">
		<div class="form__header">
			<?php
			echo $fheader;
			?>
		</div>
		<form class="form__body" action="" method="post">
			<div class="form__item">
				<label class="form__label">
					<input class="form__input form__input_text" placeholder="Имя" type="text" name="name" value="<?php echo $message['name']; ?>">
				</label>
				<?php echo $message['name-error']; ?>
			</div>
			<div class="form__item">
				<label class="form__label">
					<input class="form__input form__input_text" placeholder="E-mail" type="text" name="email" value="<?php echo $message['email']; ?>">
				</label>
				<?php echo $message['email-error']; ?>
			</div>
			<div class="form__item">
				<label class="form__label">
					<select name="year" class="form__select">
						<option value="" class="form__option">Год</option>
						<?php
						for ($i = 2008; $i >= 1900; --$i) {
							if ($i == $message['year']) {
								echo "<option class='form__option' value='$i' selected>$i</option>";
							} else {
								echo "<option class='form__option' value='$i'>$i</option>";
							}
						}
						?>
					</select>
				</label>
				<?php echo $message['year-error']; ?>
			</div>
			<div class="form__item form__item_radio">
				<div class="form__container">
					<label class="form__label">
						<?php
						if ($message['gender'] == 1) {
							echo "<input class='form__radio' type='radio' name='gender' value='1' checked>М";
						} else {
							echo "<input class='form__radio' type='radio' name='gender' value='1'>М";
						}
						?>
					</label>
					<label class="form__label">
						<?php
						if ($message['gender'] == 2) {
							echo "<input class='form__radio' type='radio' name='gender' value='2'checked>Ж";
						} else {
							echo "<input class='form__radio' type='radio' name='gender' value='2'>Ж";
						}
						?>
					</label>
				</div>
				<?php echo $message['gender-error']; ?>
			</div>
			<div class="form__item form__item_numlimbs">
				<span class="form__span">Количество конечностей</span>
				<div class="form__container">
					<?php
					for ($i = 1; $i <= 4; $i++) {
						if ($message['numlimbs'] == $i) {
							echo "<label class='form__label'><input class='form__radio' type='radio' name='numlimbs' value='$i' checked>$i</label>";
						} else {
							echo "<label class='form__label'><input class='form__radio' type='radio' name='numlimbs' value='$i'>$i</label>";
						}
					}
					?>
				</div>
				<?php echo $message['numlimbs-error']; ?>
			</div>
			<div class="form__item form__item_sp">
				<label class="form__label">
					<span class="form__span">Сверхспособности</span>
					<select multiple name="super-powers[]" class="form__select">
						<option class="form__oprion" <?php echo $message['super-powers-1'] == '1' ? 'selected' : ''; ?> value="1">Бессмертие</option>
						<option class="form__oprion" <?php echo $message['super-powers-2'] == '1' ? 'selected' : ''; ?> value="2">Прохождение сквозь стены</option>
						<option class="form__oprion" <?php echo $message['super-powers-3'] == '1' ? 'selected' : ''; ?> value="3">Левитация</option>
					</select>
				</label>
				<?php echo $message['super-powers-error'] ?>
			</div>
			<div class="form__item">
				<label class="form__label">
					<textarea class="form__textarea" placeholder="Расскажите о себе" name="biography"><?php echo $message['biography']; ?></textarea>
				</label>
				<?php echo $message['biography-error'] ?>
			</div>
			<div class="form__item form__item_agreement">
				<label class="form__label">
					<input class="form__input" type="checkbox" name="agree" required>Согласие на обработку данных
				</label>
			</div>
			<div class="form__item form__item_submit">
				<label class="form__label">
					<input class="form__submit" type="submit" value="Отправить">
				</label>
			</div>
		</form>
	</div>
</body>

</html>