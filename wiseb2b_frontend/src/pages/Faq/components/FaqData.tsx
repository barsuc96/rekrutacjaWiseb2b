// strona FAQ

import React, { FC, useEffect } from 'react';
import { ChevronUp, ChevronDown } from 'react-bootstrap-icons';
import DOMPurify from 'isomorphic-dompurify';

import { useGetFaq } from 'api';
import { IFaqRequest } from 'api/types';

import styles from 'theme/pages/Faq/Faq.module.scss';

interface IProps {
  queryParams: IFaqRequest;
  currentCategoryId: number;
  setExpandedQuestionIds: React.Dispatch<React.SetStateAction<number[]>>;
  expandedQuestionIds: number[];
}

const FaqData: FC<IProps> = ({
  queryParams,
  currentCategoryId,
  setExpandedQuestionIds,
  expandedQuestionIds
}) => {
  const { data: faqData, refetch } = useGetFaq(currentCategoryId, queryParams, { enabled: false });

  useEffect(() => {
    refetch();
  }, [currentCategoryId]);

  return (
    <div>
      {faqData?.items.map((item) => (
        <div key={item.id} className={styles['question-wrapper']}>
          <div
            className={styles.question}
            onClick={() =>
              setExpandedQuestionIds((prevState) =>
                prevState.includes(item.id)
                  ? prevState.filter((questionId) => questionId !== item.id)
                  : [...prevState, item.id]
              )
            }>
            {item.question}
            {expandedQuestionIds.includes(item.id) ? <ChevronUp /> : <ChevronDown />}
          </div>

          {expandedQuestionIds.includes(item.id) && (
            <div
              dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(item.answer) }}
              className={styles.answer}
            />
          )}
        </div>
      ))}
    </div>
  );
};

export default FaqData;
