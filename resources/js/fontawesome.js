import {
    faBan,
    faCheck,
    faInfoCircle,
    faList,
    faPlus,
    faSort,
    faSortDown,
    faSortUp,
    faTimes,
    faTrashAlt,
    faUpload,
    faSearch,
} from '@fortawesome/free-solid-svg-icons';
import { library } from '@fortawesome/fontawesome-svg-core';

/**
 * Add required icons to the icon library.
 */
export default () => {
    library.add(faCheck);
    library.add(faBan);
    library.add(faPlus);
    library.add(faList);
    library.add(faInfoCircle);
    library.add(faTimes);
    library.add(faSortUp);
    library.add(faSortDown);
    library.add(faSort);
    library.add(faTrashAlt);
    library.add(faUpload);
    library.add(faSearch);
};
