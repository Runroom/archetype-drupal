const WEB_PATH = 'web';
const WEB_ASSETS_PATH = `${WEB_PATH}/themes/custom/%t`;
const ASSETS_PATH = `assets`;
const AVAILABLE_THEMES = ['runroom'];

const FONTS_SRC = `${ASSETS_PATH}/fonts`;
const IMAGES_SRC = `${ASSETS_PATH}/img`;
const SCRIPTS_SRC = `${ASSETS_PATH}/js`;
const STYLES_SRC = `${ASSETS_PATH}/scss`;
const SPRITES_SRC = `${IMAGES_SRC}/sprites`;

const FONTS_DEST = `${WEB_ASSETS_PATH}/fonts`;
const IMAGES_DEST = `${WEB_ASSETS_PATH}/img`;
const SCRIPTS_DEST = `${WEB_ASSETS_PATH}/js`;
const STYLES_DEST = `${WEB_ASSETS_PATH}/css`;
const VIEWS_DEST = `${WEB_ASSETS_PATH}/templates`;
const SPRITES_DEST = `${VIEWS_DEST}/svg`;

export {
  AVAILABLE_THEMES,
  WEB_PATH,
  FONTS_SRC,
  IMAGES_SRC,
  SCRIPTS_SRC,
  STYLES_SRC,
  SPRITES_SRC,
  FONTS_DEST,
  IMAGES_DEST,
  SCRIPTS_DEST,
  STYLES_DEST,
  VIEWS_DEST,
  SPRITES_DEST
};
