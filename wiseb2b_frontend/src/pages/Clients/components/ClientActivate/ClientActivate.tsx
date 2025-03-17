import React from 'react';
import { Trans } from 'react-i18next';

import { usePostClientAccept } from 'api';

import { Button } from 'components/controls';

const ClientActivate = ({ id, refetchClients }: { id: number; refetchClients: () => void }) => {
  const { mutate: acceptClient } = usePostClientAccept(id, {
    onSuccess: () => {
      refetchClients();
    }
  });

  return (
    <Button onClick={() => acceptClient({ client_id: id })}>
      <Trans>Akceptacja klienta</Trans>
    </Button>
  );
};

export default ClientActivate;
