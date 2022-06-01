<?php

namespace DesoSmart\DesoClient;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
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
     * @throws DesoClientRequestException
     */
    private function handlerThrow(Response $response, RequestException $e): void
    {
        $payload = $response->json();

        if (!empty($payload['error'])) {
            throw new DesoClientException($payload['error']);
        }

        if (!empty($payload['Error'])) {
            throw new DesoClientException($payload['Error']);
        }

        throw new DesoClientRequestException($response);
    }

    private function requestGet(string $uri): array
    {
        return $this->createHttpClient()
            ->get($uri)
            ->throw(fn($res, $e) => $this->handlerThrow($res, $e))
            ->json();
    }

    private function requestPost(string $uri, array $payload = []): array
    {
        return $this->createHttpClient()
            ->post($uri, $payload)
            ->throw(fn($res, $e) => $this->handlerThrow($res, $e))
            ->json();
    }

    public function getAppState(array $payload): array
    {
        return $this->requestPost('/api/v0/get-app-state', $payload);
    }

    public function getExchangeRate(): array
    {
        return $this->requestGet('/api/v0/get-exchange-rate');
    }

    public function healthCheck(): array
    {
        return $this->requestGet('/api/v0/health-check');
    }

    public function getDAOCoinLimitOrders(array $payload): array
    {
        return $this->requestPost('/api/v0/get-dao-coin-limit-orders', $payload);
    }

    public function getTransactorDAOCoinLimitOrders(array $payload): array
    {
        return $this->requestPost('/api/v0/get-transactor-dao-coin-limit-orders', $payload);
    }

    public function queryETHRPC(array $payload): array
    {
        return $this->requestPost('/api/v0/query-eth-rpc', $payload);
    }

    public function submitETHTx(array $payload): array
    {
        return $this->requestPost('/api/v0/submit-eth-tx', $payload);
    }

    public function balance(array $payload): array
    {
        return $this->requestPost('/api/v1/balance', $payload);
    }

    public function base(): array
    {
        return $this->requestGet('/api/v1');
    }

    public function block(array $payload): array
    {
        return $this->requestPost('/api/v1/block', $payload);
    }

    public function keyPair(array $payload): array
    {
        return $this->requestPost('/api/v1/key-pair', $payload);
    }

    public function transactionInfo(array $payload): array
    {
        return $this->requestPost('/api/v1/transaction-info', $payload);
    }

    public function transferDeSo(array $payload): array
    {
        return $this->requestPost('/api/v1/transfer-deso', $payload);
    }

    public function getHotFeed(array $payload): array
    {
        return $this->requestPost('/api/v0/get-hot-feed', $payload);
    }

    public function checkPartyMessagingKeys(array $payload): array
    {
        return $this->requestPost('/api/v0/check-party-messaging-keys', $payload);
    }

    public function getAllMessagingGroupKeys(array $payload): array
    {
        return $this->requestPost('/api/v0/get-all-messaging-group-keys', $payload);
    }

    public function getMessagesStateless(array $payload): array
    {
        return $this->requestPost('/api/v0/get-messages-stateless', $payload);
    }

    public function markAllMessagesRead(array $payload): array
    {
        return $this->requestPost('/api/v0/mark-all-messages-read', $payload);
    }

    public function markContactMessagesRead(array $payload): array
    {
        return $this->requestPost('/api/v0/mark-contact-messages-read', $payload);
    }

    public function registerMessagingGroupKey(array $payload): array
    {
        return $this->requestPost('/api/v0/register-messaging-group-key', $payload);
    }

    public function sendMessageStateless(array $payload): array
    {
        return $this->requestPost('/api/v0/send-message-stateless', $payload);
    }

    public function getBlockTemplate(array $payload): array
    {
        return $this->requestPost('/api/v0/get-block-template', $payload);
    }

    public function submitBlock(array $payload): array
    {
        return $this->requestPost('/api/v0/submit-block', $payload);
    }

    public function acceptNFTBid(array $payload): array
    {
        return $this->requestPost('/api/v0/accept-nft-bid', $payload);
    }

    public function acceptNFTTransfer(array $payload): array
    {
        return $this->requestPost('/api/v0/accept-nft-transfer', $payload);
    }

    public function burnNFT(array $payload): array
    {
        return $this->requestPost('/api/v0/burn-nft', $payload);
    }

    public function createNFT(array $payload): array
    {
        return $this->requestPost('/api/v0/create-nft', $payload);
    }

    public function createNFTBid(array $payload): array
    {
        return $this->requestPost('/api/v0/create-nft-bid', $payload);
    }

    public function getAcceptedBidHistory(string $postHashHex): array
    {
        return $this->requestGet('/api/v0/accepted-bid-history/'.$postHashHex);
    }

    public function getNextNFTShowcase(array $payload): array
    {
        return $this->requestPost('/api/v0/get-next-nft-showcase', $payload);
    }

    public function getNFTBidsForNFTPost(array $payload): array
    {
        return $this->requestPost('/api/v0/get-nft-bids-for-nft-post', $payload);
    }

    public function getNFTBidsForUser(array $payload): array
    {
        return $this->requestPost('/api/v0/get-nft-bids-for-user', $payload);
    }

    public function getNFTCollectionSummary(array $payload): array
    {
        return $this->requestPost('/api/v0/get-nft-collection-summary', $payload);
    }

    public function getNFTEntriesForPostHash(array $payload): array
    {
        return $this->requestPost('/api/v0/get-nft-entries-for-nft-post', $payload);
    }

    public function getNFTsCreatedByPublicKey(array $payload): array
    {
        return $this->requestPost('/api/v0/get-nfts-created-by-public-key', $payload);
    }

    public function getNFTsForUser(array $payload): array
    {
        return $this->requestPost('/api/v0/get-nfts-for-user', $payload);
    }

    public function getNFTShowcase(array $payload): array
    {
        return $this->requestPost('/api/v0/get-nft-showcase', $payload);
    }

    public function transferNFT(array $payload): array
    {
        return $this->requestPost('/api/v0/transfer-nft', $payload);
    }

    public function updateNFT(array $payload): array
    {
        return $this->requestPost('/api/v0/update-nft', $payload);
    }

    public function getDiamondedPosts(array $payload): array
    {
        return $this->requestPost('/api/v0/get-diamonded-posts', $payload);
    }

    public function getDiamondsForPost(array $payload): array
    {
        return $this->requestPost('/api/v0/get-diamonds-for-post', $payload);
    }

    public function getLikesForPost(array $payload): array
    {
        return $this->requestPost('/api/v0/get-likes-for-post', $payload);
    }

    public function getPostsForPublicKey(array $payload): array
    {
        return $this->requestPost('/api/v0/get-posts-for-public-key', $payload);
    }

    public function getPostsStateless(array $payload): array
    {
        return $this->requestPost('/api/v0/get-posts-stateless', $payload);
    }

    public function getQuoteRepostsForPost(array $payload): array
    {
        return $this->requestPost('/api/v0/get-quote-reposts-for-post', $payload);
    }

    public function getRepostsForPost(array $payload): array
    {
        return $this->requestPost('/api/v0/get-reposts-for-post', $payload);
    }

    public function getSinglePost(array $payload): array
    {
        return $this->requestPost('/api/v0/get-single-post', $payload);
    }

    public function getReferralInfoForReferralHash(array $payload): array
    {
        return $this->requestPost('/api/v0/get-referral-info-for-referral-hash', $payload);
    }

    public function getReferralInfoForUser(array $payload): array
    {
        return $this->requestPost('/api/v0/get-referral-info-for-user', $payload);
    }

    public function appendExtraData(array $payload): array
    {
        return $this->requestPost('/api/v0/append-extra-data', $payload);
    }

    public function authorizeDerivedKey(array $payload): array
    {
        return $this->requestPost('/api/v0/authorize-derived-key', $payload);
    }

    public function buyOrSellCreatorCoin(array $payload): array
    {
        return $this->requestPost('/api/v0/buy-or-sell-creator-coin', $payload);
    }

    public function createFollowTxnStateless(array $payload): array
    {
        return $this->requestPost('/api/v0/create-follow-txn-stateless', $payload);
    }

    public function createLikeStateless(array $payload): array
    {
        return $this->requestPost('/api/v0/create-like-stateless', $payload);
    }

    public function daoCoin(array $payload): array
    {
        return $this->requestPost('/api/v0/dao-coin', $payload);
    }

    public function exchangeBitcoin(array $payload): array
    {
        return $this->requestPost('/api/v0/exchange-bitcoin', $payload);
    }

    public function getTransactionSpending(array $payload): array
    {
        return $this->requestPost('/api/v0/get-transaction-spending', $payload);
    }

    public function getTxn(array $payload): array
    {
        return $this->requestPost('/api/v0/get-txn', $payload);
    }

    public function sendDeSo(array $payload): array
    {
        return $this->requestPost('/api/v0/send-deso', $payload);
    }

    public function sendDiamonds(array $payload): array
    {
        return $this->requestPost('/api/v0/send-diamonds', $payload);
    }

    public function submitPost(array $payload): array
    {
        return $this->requestPost('/api/v0/submit-post', $payload);
    }

    public function submitTransaction(array $payload): array
    {
        return $this->requestPost('/api/v0/submit-transaction', $payload);
    }

    public function transferCreatorCoin(array $payload): array
    {
        return $this->requestPost('/api/v0/transfer-creator-coin', $payload);
    }

    public function transferDAOCoin(array $payload): array
    {
        return $this->requestPost('/api/v0/transfer-dao-coin', $payload);
    }

    public function updateProfile(array $payload): array
    {
        return $this->requestPost('/api/v0/update-profile', $payload);
    }

    public function createDAOCoinLimitOrder(array $payload): array
    {
        return $this->requestPost('/api/v0/create-dao-coin-limit-order', $payload);
    }

    public function cancelDAOCoinLimitOrder(array $payload): array
    {
        return $this->requestPost('/api/v0/cancel-dao-coin-limit-order', $payload);
    }

    public function createDAOCoinMarketOrder(array $payload): array
    {
        return $this->requestPost('/api/v0/create-dao-coin-market-order', $payload);
    }

    public function getTutorialCreators(array $payload): array
    {
        return $this->requestPost('/api/v0/get-tutorial-creators', $payload);
    }

    public function startOrSkipTutorial(array $payload): array
    {
        return $this->requestPost('/api/v0/start-or-skip-tutorial', $payload);
    }

    public function updateTutorialStatus(array $payload): array
    {
        return $this->requestPost('/api/v0/update-tutorial-status', $payload);
    }

    public function blockPublicKey(array $payload): array
    {
        return $this->requestPost('/api/v0/block-public-key', $payload);
    }

    public function deletePII(array $payload): array
    {
        return $this->requestPost('/api/v0/delete-pii', $payload);
    }

    public function getDiamondsForPublicKey(array $payload): array
    {
        return $this->requestPost('/api/v0/get-diamonds-for-public-key', $payload);
    }

    public function getFollowsStateless(array $payload): array
    {
        return $this->requestPost('/api/v0/get-follows-stateless', $payload);
    }

    public function getHodlersForPublicKey(array $payload): array
    {
        return $this->requestPost('/api/v0/get-hodlers-for-public-key', $payload);
    }

    public function getNotifications(array $payload): array
    {
        return $this->requestPost('/api/v0/get-notifications', $payload);
    }

    public function getProfiles(array $payload): array
    {
        return $this->requestPost('/api/v0/get-profiles', $payload);
    }

    public function getSingleProfile(array $payload): array
    {
        return $this->requestPost('/api/v0/get-single-profile', $payload);
    }

    public function getUnreadNotificationsCount(array $payload): array
    {
        return $this->requestPost('/api/v0/get-unread-notifications-count', $payload);
    }

    public function getUserDerivedKeys(array $payload): array
    {
        return $this->requestPost('/api/v0/get-user-derived-keys', $payload);
    }

    public function getUserGlobalMetadata(array $payload): array
    {
        return $this->requestPost('/api/v0/get-user-global-metadata', $payload);
    }

    public function getUserMetadata(string $publicKeyBase58Check): array
    {
        return $this->requestGet('/api/v0/get-user-metadata/'.$publicKeyBase58Check);
    }

    public function getUsersStateless(array $payload): array
    {
        return $this->requestPost('/api/v0/get-users-stateless', $payload);
    }

    public function isFollowingPublicKey(array $payload): array
    {
        return $this->requestPost('/api/v0/is-following-public-key', $payload);
    }

    public function isHodlingPublicKey(array $payload): array
    {
        return $this->requestPost('/api/v0/is-hodling-public-key', $payload);
    }

    public function setNotificationMetadata(array $payload): array
    {
        return $this->requestPost('/api/v0/set-notification-metadata', $payload);
    }

    public function updateUserGlobalMetadata(array $payload): array
    {
        return $this->requestPost('/api/v0/update-user-global-metadata', $payload);
    }

    public function getTransactionSpendingLimitHexString(array $payload): array
    {
        return $this->requestPost('/api/v0/get-transaction-spending-limit-hex-string', $payload);
    }

    public function getTransactionSpendingLimitResponseFromHex(string $transactionSpendingLimitHex): array
    {
        return $this->requestGet(
            '/api/v0/get-transaction-spending-limit-response-from-hex/'.$transactionSpendingLimitHex
        );
    }
}

