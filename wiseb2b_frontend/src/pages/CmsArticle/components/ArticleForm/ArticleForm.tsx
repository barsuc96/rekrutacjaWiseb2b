import React, { FC, useState, useEffect, useMemo, Dispatch, SetStateAction } from 'react';
import { format } from 'date-fns';
import { useNavigate } from 'react-router-dom';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';
import { useFormik } from 'formik';
import Textarea from '@mui/material/TextareaAutosize';

import { reduxActions, useDispatch } from 'store';
import { useGetCmsSectionFields, useGetCmsArticleCountries, useGetCmsLayouts } from 'api';
import {
  ICmsSectionItem,
  ICmsArticleRequest,
  ICmsArticleResponse,
  ICmsArticleCountryListItem,
  ICmsLayoutsItem
} from 'api/types';

import {
  Input,
  Button,
  FormElement,
  Checkbox,
  Select,
  DateRangePicker,
  PageTitle,
  TranslatedInput,
  TranslatedEditor
} from 'components/controls';

import styles from 'theme/pages/Cms/components/ArticleForm.module.scss';

interface IQueryParam {
  language: string;
}

// typ danych wejściowych
interface IProps {
  sectionList: ICmsSectionItem[];
  isLoading: boolean;
  mutate: (arg: ICmsArticleRequest) => void;
  data?: ICmsArticleResponse;
  setQueryParams?: Dispatch<SetStateAction<IQueryParam>>;
}

const ArticleForm: FC<IProps> = ({ sectionList, isLoading, mutate, data, setQueryParams }) => {
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const { t } = useTranslation();

  // aktualnie wybrana sekcja
  const [selectedSection, setSelectedSection] = useState<number>(0);

  // aktualnie wybrany kraj
  const [selectedCountry, setSelectedCountry] = useState<string>('');

  // pobieranie danych sekcji
  const { data: sectionFieldsData, refetch: refetchSectionFieldsData } = useGetCmsSectionFields(
    selectedSection,
    {},
    { enabled: false }
  );

  // pobieranie listy możliwych krajów
  const { data: articleCountries } = useGetCmsArticleCountries();

  // pobieranie listy możliwych layoutów
  const { data: cmsLayouts } = useGetCmsLayouts();

  // Ustawienie breadcrumbs'ów (przy renderowaniu strony)
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        {
          name: t('Dashboard'),
          path: '/dashboard'
        },
        {
          name: t('Lista artykułów'),
          path: '/dashboard/cms/articles'
        },
        {
          name:
            data?.title.find((o) => o.language === selectedCountry)?.translation ||
            t('Utwórz artykuł'),
          path: undefined
        }
      ])
    );
  }, [data, selectedCountry]);

  // pobieranie danych sekcji
  useEffect(() => {
    if (selectedSection) {
      refetchSectionFieldsData();
    }
  }, [selectedSection]);

  // uaktualnienie sekcji przy edycji danych
  useEffect(() => {
    if (data) {
      setSelectedSection(data.section_id);
    }
  }, [data]);

  useEffect(() => {
    if (articleCountries?.items) {
      setSelectedCountry(articleCountries.items[0].code);
    }
  }, [articleCountries]);

  // ustawienie queryParamnetrów przy zmianie języka
  useEffect(() => {
    if (selectedCountry && setQueryParams) {
      setQueryParams({ language: selectedCountry });
    }
  }, [selectedCountry]);

  // ustawianie domyśnych pól dla pól formularza
  useEffect(() => {
    if (sectionFieldsData) {
      const fields = sectionFieldsData.items.map((item) => ({
        section_field_id: item.id,
        type: item.type,
        symbol: item.symbol,
        value: null,
        file: null
      }));

      setFieldValue(
        'article_fields',
        selectedSection === data?.section_id ? data?.article_fields : fields
      );
    }
  }, [sectionFieldsData]);

  // obsługa formularza
  const { values, errors, setFieldValue, handleSubmit } = useFormik({
    initialValues: {
      section_id: data?.section_id,
      symbol: data?.symbol || '',
      title: data?.title || null,
      language: data?.language || articleCountries?.items[0].code || '',
      from_date: data?.from_date || null,
      to_date: data?.to_date || null,
      is_active: data?.is_active || false,
      article_fields: data?.article_fields || [],
      layouts: data?.layouts || null
    },
    onSubmit: async (values) => {
      await mutate(values);

      navigate(-1);
    },
    validateOnChange: false,
    enableReinitialize: true
  });

  // opcje dropdownu typów pola
  const selectOptions = useMemo(() => {
    const sections = sectionList.map((section) => ({
      value: section.id,
      label: <span>{section.name}</span>,
      item: section
    }));

    return sections;
  }, [sectionList]);

  // opcje dropdownu typów pola
  const countryOptions = useMemo(() => {
    const sections = (articleCountries?.items || []).map((country) => ({
      value: country.code,
      label: <span>{country.name}</span>,
      item: country
    }));

    return sections;
  }, [articleCountries]);

  // opcje dropdownu typów pola
  const layoutOptions = useMemo(() => {
    const layouts = (cmsLayouts?.items || []).map((layout) => ({
      value: layout.symbol,
      label: <span>{layout.name}</span>,
      item: { ...layout, value: layout.name }
    }));

    return layouts;
  }, [cmsLayouts]);

  const renderArticleField = (type: string, sectionId: number, index: number) => {
    const field = values.article_fields.find((field) => field.section_field_id === sectionId);

    switch (type) {
      case 'STRING':
        return (
          <Textarea
            minRows={5}
            value={field?.value || ''}
            onChange={({ target: { value } }) =>
              setFieldValue(`article_fields[${index}].value`, value)
            }
          />
        );
      case 'HTMLBLOCK':
        return (
          <div>
            <TranslatedEditor
              name={`article_fields[${index}].value`}
              value={field?.value || []}
              selectedCountry={selectedCountry}
              setFieldValue={setFieldValue}
              countries={articleCountries?.items || []}
            />
          </div>
        );
      case 'IMAGE':
        return (
          <div>
            <input
              onChange={({ target: { files } }) => files && handleArticleFileField(files[0], index)}
              type="file"
            />
            <div className={styles.fileWrapper}>
              <img src={field?.file?.url || field?.file?.base64 || ''} />
              <div>{field?.file?.name || ''}</div>
            </div>
          </div>
        );
      case 'FILE':
        return (
          <div>
            <input
              onChange={({ target: { files } }) => files && handleArticleFileField(files[0], index)}
              type="file"
            />
            <div>{field?.file?.name || ''}</div>
          </div>
        );
      case 'BOOL':
        return (
          <Checkbox
            checked={field?.value === 'true' ? true : false}
            onClick={() => {
              if (field?.value === 'true') {
                setFieldValue(`article_fields[${index}].value`, 'false');
              } else {
                setFieldValue(`article_fields[${index}].value`, 'true');
              }
            }}
          />
        );
      case 'INTEGER':
        return (
          <input
            type="number"
            step={1}
            value={field?.value || ''}
            onChange={({ target: { value } }) =>
              setFieldValue(`article_fields[${index}].value`, value)
            }
          />
        );
      case 'DECIMAL':
        return (
          <input
            type="number"
            step="any"
            value={field?.value || ''}
            onChange={({ target: { value } }) =>
              setFieldValue(`article_fields[${index}].value`, value)
            }
          />
        );

      default:
        return null;
    }
  };

  const renderLabel = (sectionId: number, index: number) => {
    const label = sectionFieldsData?.items.find((field) => field.id === sectionId)?.label;

    return (
      <label>
        <span>{`${index + 1}.`}</span> {label}
      </label>
    );
  };

  const handleArticleFileField = (file: File, index: number) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
      setFieldValue(`article_fields[${index}].file`, {
        base64: reader.result,
        name: file.name
      });
    };
  };

  // lista pól artykułu
  const articleFields = useMemo(() => {
    // sytuacja, kiedy edytujemy artykuł
    if (data?.article_fields) {
      const fields = (data?.article_fields || []).map((field, index) => (
        <FormElement key={field.section_field_id}>
          {renderLabel(field.section_field_id, index)}
          {renderArticleField(field.type, field.section_field_id, index)}
        </FormElement>
      ));

      return fields;
    }

    //sytuacja kiedy tworzymy artykuł
    const fields = (sectionFieldsData?.items || []).map((field, index) => (
      <FormElement key={field.id}>
        <label>
          <span>{`${index + 1}.`}</span> {field.label}
        </label>
        {renderArticleField(field.type, field.id, index)}
      </FormElement>
    ));

    return fields;
  }, [sectionFieldsData, data, values, selectedCountry]);

  return (
    <div
      className={classnames(styles.componentWrapper, 'StylePath-Pages-Cms-components-ArticleForm')}>
      <PageTitle
        title={
          <div className={styles.titleWrapper}>
            <div className={styles.text}>
              <Trans>{data ? 'Edytuj artykuł' : 'Utwórz artykuł'}</Trans>
              {!!data &&
                ' - ' + data.title.find((o) => o.language === selectedCountry)?.translation}
            </div>
          </div>
        }
      />
      <div>
        <div className={styles.contentWrapper}>
          <div className={styles.inputWrapper}>
            <FormElement halfWidth>
              <label>
                <Trans>Sekcja</Trans>
              </label>
              <Select<ICmsSectionItem>
                options={selectOptions}
                variant="default"
                value={values.section_id}
                disabled={!!data}
                onChange={(item) => {
                  setFieldValue('section_id', item?.id);
                  setSelectedSection(item?.id || 0);
                }}
              />
            </FormElement>
            <FormElement halfWidth>
              <label>
                <Trans>Język</Trans>
              </label>
              <Select<ICmsArticleCountryListItem>
                options={countryOptions}
                variant="default"
                value={selectedCountry}
                onChange={(item) => {
                  if (item?.code) {
                    setSelectedCountry(item.code);
                    setFieldValue('language', item.code);
                  }
                }}
              />
            </FormElement>
          </div>

          <div className={styles.inputWrapper}>
            <FormElement halfWidth>
              <label>
                <Trans>Symbol</Trans>
              </label>
              <Input
                value={values.symbol}
                onChange={(value) => setFieldValue('symbol', value)}
                error={errors.symbol}
              />
            </FormElement>
            <FormElement halfWidth>
              <label>
                <Trans>Nazwa</Trans>
              </label>
              <TranslatedInput
                name="title"
                value={values.title}
                selectedCountry={selectedCountry}
                setFieldValue={setFieldValue}
                countries={articleCountries?.items || []}
              />
            </FormElement>
          </div>

          <div className={styles.inputWrapper}>
            <FormElement halfWidth>
              <label>Layout</label>
              <Select<ICmsLayoutsItem>
                options={layoutOptions}
                variant="default"
                value={values.layouts}
                disabled={!cmsLayouts}
                clearable
                onChange={(items) => {
                  const layoutValue =
                    Array.isArray(items) &&
                    items
                      ?.map((item) => item.item.name)
                      .toString()
                      .replace(',', '|');
                  setFieldValue('layouts', layoutValue);
                }}
                isMulti
                error={errors.layouts}
              />
            </FormElement>
            <FormElement halfWidth>
              <label>
                <Trans>Zakres dat w których artykuł ma się wyświetlać:</Trans>
              </label>
              <div className={styles.dateWrapper}>
                <DateRangePicker
                  dateRange={{
                    from: values.from_date || undefined,
                    to: values.to_date || undefined
                  }}
                  onChange={(range) => {
                    setFieldValue('from_date', format(new Date(range.from), 'yyyy-MM-dd'));
                    setFieldValue('to_date', format(new Date(range.to), 'yyyy-MM-dd'));
                  }}
                />
                <Button
                  onClick={() => {
                    setFieldValue('from_date', null);
                    setFieldValue('to_date', null);
                  }}>
                  {t('Wyczyść daty')}
                </Button>
                <div className={styles.active}>
                  <label>
                    <Trans>Aktywny</Trans>
                  </label>
                  <Checkbox
                    checked={values.is_active}
                    onClick={() => setFieldValue('is_active', !values.is_active)}
                  />
                </div>
              </div>
            </FormElement>
          </div>
          <div>
            <h2>
              <Trans>Pola artykułu</Trans>
            </h2>
            <div className={styles.fields}>{articleFields}</div>
          </div>
        </div>
        <div className={styles.actions}>
          <Button color="primary" onClick={() => handleSubmit()} loading={isLoading}>
            <Trans>Zapisz</Trans>
          </Button>
        </div>
      </div>
    </div>
  );
};

export default ArticleForm;
