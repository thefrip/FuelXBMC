<div class="page-header">
    <h1><?php echo $page_title; ?></h1>
</div>

<?php echo Form::open(array('class' => 'form-horizontal', 'id' => 'settings')); ?>

  <ul class="nav nav-pills">
    <li class="active"><a href="#general" data-toggle="tab"><?php echo Lang::get('navigation.general'); ?></a></li>
    <li><a href="#media" data-toggle="tab"><?php echo Lang::get('navigation.media'); ?></a></li>
    <li><a href="#home" data-toggle="tab"><?php echo Lang::get('navigation.home'); ?></a></li>
  </ul>

  <div class="tab-content">

    <div class="tab-pane active" id="general">
      <div class="row">
        <div class="span8">

          <fieldset>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.tvshow_poster'), 'tvshow_poster', array('class'=>'control-label')); ?>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" name="tvshow_poster" value="banner" <?php echo ($settings['tvshow_poster'] == 'banner') ? 'checked' : '' ?>>
                  <?php echo Lang::get('setting.banner'); ?>
                </label>
                <label class="radio inline">
                  <input type="radio" name="tvshow_poster" value="poster" <?php echo ($settings['tvshow_poster'] != 'banner') ? 'checked' : '' ?>>
                  <?php echo Lang::get('setting.poster'); ?>
                </label>
                <span class="help-block"><?php echo Lang::get('setting.tvshow_poster_help'); ?></span>
              </div>
            </div>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.private_site'), 'private_site', array('class'=>'control-label')); ?>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" name="private_site" value="1" <?php echo $settings['private_site'] ? 'checked' : '' ?>>
                  <?php echo Lang::get('global.yes'); ?>
                </label>
                <label class="radio inline">
                  <input type="radio" name="private_site" value="0" <?php echo $settings['private_site'] ? '' : 'checked' ?>>
                  <?php echo Lang::get('global.no'); ?>
                </label>
                <span class="help-block"><?php echo Lang::get('setting.private_site_help'); ?></span>
              </div>
            </div>

            <?php if ($languages): ?>
              <div class="control-group">
                <?php echo Form::label(Lang::get('setting.language'), 'language', array('class'=>'control-label')); ?>
                <div class="controls">
                  <select name="language" >
                    <?php foreach($languages as $key => $value): ?>
                      <option value="<?php echo $key; ?>" <?php echo ($key == Config::get('language')) ? 'selected="selected"' : '';?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php endif; ?>

          </fieldset>

        </div>
      </div>
    </div><!--/.tab-pane -->

    <div class="tab-pane" id="media">
      <div class="row">
        <div class="span12">

          <fieldset>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.manage_music'), 'manage_music', array('class'=>'control-label')); ?>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" id="yes_albums" name="manage_music" value="1" <?php echo $settings['manage_music'] ? 'checked' : '' ?>>
                  <?php echo Lang::get('global.yes'); ?>
                </label>
                <label class="radio inline">
                  <input type="radio" id="no_albums" name="manage_music" value="0" <?php echo $settings['manage_music'] ? '' : 'checked' ?>>
                  <?php echo Lang::get('global.no'); ?>
                </label>
              </div>
            </div>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.manage_movies'), 'manage_movie', array('class'=>'control-label')); ?>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" id="yes_movies" name="manage_movies" value="1" <?php echo $settings['manage_movies'] ? 'checked' : '' ?>>
                  <?php echo Lang::get('global.yes'); ?>
                </label>
                <label class="radio inline">
                  <input type="radio" id="no_movies" name="manage_movies" value="0" <?php echo $settings['manage_movies'] ? '' : 'checked' ?>>
                  <?php echo Lang::get('global.no'); ?>
                </label>
              </div>
            </div>

            <div class="control-group" id="group_manage_sets">
              <?php echo Form::label(Lang::get('setting.manage_sets'), 'manage_set', array('class'=>'control-label')); ?>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" name="manage_sets" value="1" <?php echo $settings['manage_sets'] ? 'checked' : '' ?>>
                  <?php echo Lang::get('global.yes'); ?>
                </label>
                <label class="radio inline">
                  <input type="radio" name="manage_sets" value="0" <?php echo $settings['manage_sets'] ? '' : 'checked' ?>>
                  <?php echo Lang::get('global.no'); ?>
                </label>
              </div>
            </div>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.manage_tvshows'), 'manage_tvshow', array('class'=>'control-label')); ?>
              <div class="controls">
                <label class="radio inline">
                  <input type="radio" id="yes_tvshows" name="manage_tvshows" value="1" <?php echo $settings['manage_tvshows'] ? 'checked' : '' ?>>
                  <?php echo Lang::get('global.yes'); ?>
                </label>
                <label class="radio inline">
                  <input type="radio" id="no_tvshows" name="manage_tvshows" value="0" <?php echo $settings['manage_tvshows'] ? '' : 'checked' ?>>
                  <?php echo Lang::get('global.no'); ?>
                </label>
              </div>
            </div>

          </fieldset>

        </div>
      </div>
    </div><!--/.tab-pane -->

    <div class="tab-pane" id="home">
      <div class="row">
        <div class="span12">

          <fieldset>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.last_albums'), 'last_albums', array('class'=>'control-label')); ?>
              <div class="controls">
                <?php echo Form::input('last_albums', Input::post('last_albums', $settings['last_albums']), array('class' => 'input-mini', 'id' => 'input_last_albums')); ?>
                <span class="label label-warning warning-manage" id="warning_no_albums"><?php echo Lang::get('setting.warning_albums'); ?></span>
              </div>
            </div>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.last_movies'), 'last_movies', array('class'=>'control-label')); ?>
              <div class="controls">
                <?php echo Form::input('last_movies', Input::post('last_movies', $settings['last_movies']), array('class' => 'input-mini', 'id' => 'input_last_movies')); ?>
                <span class="label label-warning warning-manage" id="warning_no_movies"><?php echo Lang::get('setting.warning_movies'); ?></span>
              </div>
            </div>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.last_tvshows'), 'last_tvshows', array('class'=>'control-label')); ?>
              <div class="controls">
                <?php echo Form::input('last_tvshows', Input::post('last_tvshows', $settings['last_tvshows']), array('class' => 'input-mini', 'id' => 'input_last_tvshows')); ?>
                <span class="label label-warning warning-manage" id="warning_no_tvshows"><?php echo Lang::get('setting.warning_tvshows'); ?></span>
              </div>
            </div>

            <div class="control-group">
              <?php echo Form::label(Lang::get('setting.last_episodes'), 'last_episodes', array('class'=>'control-label')); ?>
              <div class="controls">
                <?php echo Form::input('last_episodes', Input::post('last_episodes', $settings['last_episodes']), array('class' => 'input-mini', 'id' => 'input_last_episodes')); ?>
                <span class="label label-warning warning-manage" id="warning_no_episodes"><?php echo Lang::get('setting.warning_tvshows'); ?></span>
              </div>
              <span class="help-block"><?php echo Lang::get('setting.home_help'); ?></span>
            </div>

          </fieldset>

        </div>
      </div>
    </div><!--/.tab-pane -->

  </div><!--/.tab-content -->
  <hr>
  <button type="submit" class="btn btn-success">
    <i class="icon-ok icon-white"></i> <?php echo Lang::get('global.btn_save_change'); ?>
  </button>
<?php echo Form::close(); ?>
