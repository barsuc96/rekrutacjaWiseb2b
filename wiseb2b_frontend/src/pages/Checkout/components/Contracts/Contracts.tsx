// sekcja ze szczegółami na podsumowaniu

import React, { FC, useState, useEffect, Dispatch, SetStateAction } from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';
import each from 'lodash/each';

import { usePostCheckoutAgreementToggle } from 'api';
import { ICheckoutAgreementListItem } from 'api/types';
import { Checkbox } from 'components/controls';

import styles from 'theme/pages/Checkout/components/Details/Details.module.scss';

// typ danych wejściowych
interface IProps {
  cartId: number;
  contracts: ICheckoutAgreementListItem[];
  selectedContractIds: number[];
  setSelectedContractIds: Dispatch<SetStateAction<number[]>>;
  refetchCheckoutAgreementData: () => void;
}

const Contracts: FC<IProps> = ({ cartId, contracts, refetchCheckoutAgreementData }) => {
  // wybrane zgody
  const [selectedContractIds, setSelectedContractIds] = useState<number[]>([]);

  // zmiana zgód
  const { mutate: updateContractAgreement } = usePostCheckoutAgreementToggle();

  // ustawienie aktywnych zgód
  useEffect(() => {
    if (contracts) {
      const contractIds: number[] = [];
      each(contracts, (contract) => {
        if (contract.has_active_agree) {
          contractIds.push(contract.id);
        }
      });

      setSelectedContractIds(contractIds);
    }
  }, [contracts]);

  const handleContractClick = (contract: ICheckoutAgreementListItem) => {
    updateContractAgreement(
      {
        items: [
          {
            contractId: contract.id,
            isAgree: !contract.has_active_agree,
            cartId: cartId
          }
        ]
      },
      {
        onSuccess: () => {
          refetchCheckoutAgreementData();

          if (selectedContractIds.some((item) => item === contract.id)) {
            setSelectedContractIds((prevState) => prevState.filter((id) => id !== contract.id));
            return;
          }

          setSelectedContractIds((prevState) => [...prevState, contract.id]);
        }
      }
    );
  };

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Checkout-Components-Contracts'
      )}>
      {contracts.map((contract, i) => (
        <div key={i}>
          <div>
            <b>{contract.name}</b>
          </div>
          <div>
            <Checkbox
              onClick={() => handleContractClick(contract)}
              checked={!!selectedContractIds.find((id) => id === contract.id)}
            />{' '}
            <span dangerouslySetInnerHTML={{ __html: contract.testimony }} />{' '}
            {contract.user_must_accept && <b>*</b>}
          </div>
        </div>
      ))}
      {contracts.some((contract) => contract.user_must_accept) && (
        <div>
          <b>*</b> - <Trans>Zgoda Wymagana</Trans>
        </div>
      )}
    </div>
  );
};

export default Contracts;
