<?php

namespace DesoSmart\DesoClient;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class DesoClient
{
    public function __construct(private string $nodeUri)
    {
    }

    private function createHttpClient(): PendingRequest
    {
        return Http::asJson()->baseUrl($this->nodeUri);
    }

    /**
     * @throws DesoClientException
     */
    private function handlerThrow(Response $response, RequestException $e): void
    {
        $payload = $response->json();

        if (!empty($payload['error'])) {
            throw new DesoClientException($payload['error'], $e->getCode(), $e);
        }

        if (!empty($payload['Error'])) {
            throw new DesoClientException($payload['Error'], $e->getCode(), $e);
        }

        throw new DesoClientException($e->getMessage(), $e->getCode(), $e);
    }

    private function requestGet(string $uri): Collection
    {
        return $this->createHttpClient()
            ->get($uri)
            ->throw(fn($res, $e) => $this->handlerThrow($res, $e))
            ->collect();
    }

    private function requestPost(string $uri, array $payload = []): Collection
    {
        return $this->createHttpClient()
            ->post($uri, $payload)
            ->throw(fn($res, $e) => $this->handlerThrow($res, $e))
            ->collect();
    }

    public function getAppState(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-app-state', $payload);
    }

    public function getExchangeRate(): Collection
    {
        return $this->requestGet('/api/v0/get-exchange-rate');
    }

    public function healthCheck(): Collection
    {
        return $this->requestGet('/api/v0/health-check');
    }

    public function getDAOCoinLimitOrders(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-dao-coin-limit-orders', $payload);
    }

    public function getTransactorDAOCoinLimitOrders(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-transactor-dao-coin-limit-orders', $payload);
    }

    public function queryETHRPC(array $payload): Collection
    {
        return $this->requestPost('/api/v0/query-eth-rpc', $payload);
    }

    public function submitETHTx(array $payload): Collection
    {
        return $this->requestPost('/api/v0/submit-eth-tx', $payload);
    }

    public function balance(array $payload): Collection
    {
        return $this->requestPost('/api/v1/balance', $payload);
    }

    public function base(): Collection
    {
        return $this->requestGet('/api/v1');
    }

    public function block(array $payload): Collection
    {
        return $this->requestPost('/api/v1/block', $payload);
    }

    public function keyPair(array $payload): Collection
    {
        return $this->requestPost('/api/v1/key-pair', $payload);
    }

    public function transactionInfo(array $payload): Collection
    {
        return $this->requestPost('/api/v1/transaction-info', $payload);
    }

    public function transferDeSo(array $payload): Collection
    {
        return $this->requestPost('/api/v1/transfer-deso', $payload);
    }

    public function getHotFeed(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-hot-feed', $payload);
    }

    public function checkPartyMessagingKeys(array $payload): Collection
    {
        return $this->requestPost('/api/v0/check-party-messaging-keys', $payload);
    }

    public function getAllMessagingGroupKeys(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-all-messaging-group-keys', $payload);
    }

    public function getMessagesStateless(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-messages-stateless', $payload);
    }

    public function markAllMessagesRead(array $payload): Collection
    {
        return $this->requestPost('/api/v0/mark-all-messages-read', $payload);
    }

    public function markContactMessagesRead(array $payload): Collection
    {
        return $this->requestPost('/api/v0/mark-contact-messages-read', $payload);
    }

    public function registerMessagingGroupKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/register-messaging-group-key', $payload);
    }

    public function sendMessageStateless(array $payload): Collection
    {
        return $this->requestPost('/api/v0/send-message-stateless', $payload);
    }

    public function getBlockTemplate(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-block-template', $payload);
    }

    public function submitBlock(array $payload): Collection
    {
        return $this->requestPost('/api/v0/submit-block', $payload);
    }

    public function acceptNFTBid(array $payload): Collection
    {
        return $this->requestPost('/api/v0/accept-nft-bid', $payload);
    }

    public function acceptNFTTransfer(array $payload): Collection
    {
        return $this->requestPost('/api/v0/accept-nft-transfer', $payload);
    }

    public function burnNFT(array $payload): Collection
    {
        return $this->requestPost('/api/v0/burn-nft', $payload);
    }

    public function createNFT(array $payload): Collection
    {
        return $this->requestPost('/api/v0/create-nft', $payload);
    }

    public function createNFTBid(array $payload): Collection
    {
        return $this->requestPost('/api/v0/create-nft-bid', $payload);
    }

    public function getAcceptedBidHistory(string $postHashHex): Collection
    {
        return $this->requestGet('/api/v0/accepted-bid-history/'.$postHashHex);
    }

    public function getNextNFTShowcase(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-next-nft-showcase', $payload);
    }

    public function getNFTBidsForNFTPost(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-nft-bids-for-nft-post', $payload);
    }

    public function getNFTBidsForUser(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-nft-bids-for-user', $payload);
    }

    public function getNFTCollectionSummary(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-nft-collection-summary', $payload);
    }

    public function getNFTEntriesForPostHash(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-nft-entries-for-nft-post', $payload);
    }

    public function getNFTsCreatedByPublicKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-nfts-created-by-public-key', $payload);
    }

    public function getNFTsForUser(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-nfts-for-user', $payload);
    }

    public function getNFTShowcase(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-nft-showcase', $payload);
    }

    public function transferNFT(array $payload): Collection
    {
        return $this->requestPost('/api/v0/transfer-nft', $payload);
    }

    public function updateNFT(array $payload): Collection
    {
        return $this->requestPost('/api/v0/update-nft', $payload);
    }

    public function getDiamondedPosts(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-diamonded-posts', $payload);
    }

    public function getDiamondsForPost(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-diamonds-for-post', $payload);
    }

    public function getLikesForPost(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-likes-for-post', $payload);
    }

    public function getPostsForPublicKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-posts-for-public-key', $payload);
    }

    public function getPostsStateless(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-posts-stateless', $payload);
    }

    public function getQuoteRepostsForPost(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-quote-reposts-for-post', $payload);
    }

    public function getRepostsForPost(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-reposts-for-post', $payload);
    }

    public function getSinglePost(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-single-post', $payload);
    }

    public function getReferralInfoForReferralHash(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-referral-info-for-referral-hash', $payload);
    }

    public function getReferralInfoForUser(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-referral-info-for-user', $payload);
    }

    public function appendExtraData(array $payload): Collection
    {
        return $this->requestPost('/api/v0/append-extra-data', $payload);
    }

    public function authorizeDerivedKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/authorize-derived-key', $payload);
    }

    public function buyOrSellCreatorCoin(array $payload): Collection
    {
        return $this->requestPost('/api/v0/buy-or-sell-creator-coin', $payload);
    }

    public function createFollowTxnStateless(array $payload): Collection
    {
        return $this->requestPost('/api/v0/create-follow-txn-stateless', $payload);
    }

    public function createLikeStateless(array $payload): Collection
    {
        return $this->requestPost('/api/v0/create-like-stateless', $payload);
    }

    public function daoCoin(array $payload): Collection
    {
        return $this->requestPost('/api/v0/dao-coin', $payload);
    }

    public function exchangeBitcoin(array $payload): Collection
    {
        return $this->requestPost('/api/v0/exchange-bitcoin', $payload);
    }

    public function getTransactionSpending(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-transaction-spending', $payload);
    }

    public function getTxn(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-txn', $payload);
    }

    public function sendDeSo(array $payload): Collection
    {
        return $this->requestPost('/api/v0/send-deso', $payload);
    }

    public function sendDiamonds(array $payload): Collection
    {
        return $this->requestPost('/api/v0/send-diamonds', $payload);
    }

    public function submitPost(array $payload): Collection
    {
        return $this->requestPost('/api/v0/submit-post', $payload);
    }

    public function submitTransaction(array $payload): Collection
    {
        return $this->requestPost('/api/v0/submit-transaction', $payload);
    }

    public function transferCreatorCoin(array $payload): Collection
    {
        return $this->requestPost('/api/v0/transfer-creator-coin', $payload);
    }

    public function transferDAOCoin(array $payload): Collection
    {
        return $this->requestPost('/api/v0/transfer-dao-coin', $payload);
    }

    public function updateProfile(array $payload): Collection
    {
        return $this->requestPost('/api/v0/update-profile', $payload);
    }

    public function createDAOCoinLimitOrder(array $payload): Collection
    {
        return $this->requestPost('/api/v0/create-dao-coin-limit-order', $payload);
    }

    public function cancelDAOCoinLimitOrder(array $payload): Collection
    {
        return $this->requestPost('/api/v0/cancel-dao-coin-limit-order', $payload);
    }

    public function createDAOCoinMarketOrder(array $payload): Collection
    {
        return $this->requestPost('/api/v0/create-dao-coin-market-order', $payload);
    }

    public function getTutorialCreators(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-tutorial-creators', $payload);
    }

    public function startOrSkipTutorial(array $payload): Collection
    {
        return $this->requestPost('/api/v0/start-or-skip-tutorial', $payload);
    }

    public function updateTutorialStatus(array $payload): Collection
    {
        return $this->requestPost('/api/v0/update-tutorial-status', $payload);
    }

    public function blockPublicKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/block-public-key', $payload);
    }

    public function deletePII(array $payload): Collection
    {
        return $this->requestPost('/api/v0/delete-pii', $payload);
    }

    public function getDiamondsForPublicKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-diamonds-for-public-key', $payload);
    }

    public function getFollowsStateless(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-follows-stateless', $payload);
    }

    public function getHodlersForPublicKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-hodlers-for-public-key', $payload);
    }

    public function getNotifications(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-notifications', $payload);
    }

    public function getProfiles(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-profiles', $payload);
    }

    public function getSingleProfile(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-single-profile', $payload);
    }

    public function getUnreadNotificationsCount(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-unread-notifications-count', $payload);
    }

    public function getUserDerivedKeys(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-user-derived-keys', $payload);
    }

    public function getUserGlobalMetadata(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-user-global-metadata', $payload);
    }

    public function getUserMetadata(string $publicKeyBase58Check): Collection
    {
        return $this->requestGet('/api/v0/get-user-metadata/'.$publicKeyBase58Check);
    }

    public function getUsersStateless(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-users-stateless', $payload);
    }

    public function isFollowingPublicKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/is-following-public-key', $payload);
    }

    public function isHodlingPublicKey(array $payload): Collection
    {
        return $this->requestPost('/api/v0/is-hodling-public-key', $payload);
    }

    public function setNotificationMetadata(array $payload): Collection
    {
        return $this->requestPost('/api/v0/set-notification-metadata', $payload);
    }

    public function updateUserGlobalMetadata(array $payload): Collection
    {
        return $this->requestPost('/api/v0/update-user-global-metadata', $payload);
    }

    public function getTransactionSpendingLimitHexString(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-transaction-spending-limit-hex-string', $payload);
    }

    public function getTransactionSpendingLimitResponseFromHex(string $transactionSpendingLimitHex): Collection
    {
        return $this->requestGet(
            '/api/v0/get-transaction-spending-limit-response-from-hex/'.$transactionSpendingLimitHex
        );
    }

    public function metamaskSignIn(array $payload): Collection
    {
        return $this->requestPost('/api/v0/send-starter-deso-for-metamask-account', $payload);
    }

    public function getBulkMessagingPublicKeys(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-bulk-messaging-public-keys', $payload);
    }

    public function getSingleDerivedKey(
        string $ownerPublicKeyBase58Check,
        string $derivedPublicKeyBase58Check
    ): Collection {
        return $this->requestGet(
            '/api/v0/get-single-derived-key/'.$ownerPublicKeyBase58Check.'/'.$derivedPublicKeyBase58Check
        );
    }

    public function getAccessBytes(array $payload): Collection
    {
        return $this->requestPost('/api/v0/get-access-bytes', $payload);
    }
}
