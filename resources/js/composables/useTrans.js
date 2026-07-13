import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ar from '@/lang/ar';
import en from '@/lang/en';

const dictionaries = { ar, en };

export function useTrans() {
    const page = usePage();
    const locale = computed(() => page.props.locale || 'ar');

    function t(key, replacements = {}) {
        const dictionary = dictionaries[locale.value] || dictionaries.ar;
        let value = dictionary[key] ?? key;

        Object.entries(replacements).forEach(([search, replace]) => {
            value = value.replace(`:${search}`, replace);
        });

        return value;
    }

    return { t, locale };
}
